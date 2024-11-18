<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Services\FileService;
use App\Http\Requests\DetailCreateRequest;
use App\Http\Requests\DetailUpdateRequest;
use App\Repositories\DetailRepository;
use App\Models\Detail;
use App\Exceptions\EntityNotFoundException;
use Illuminate\Support\Str;

class DetailService
{
    private $fileService;

    private $repository;

    public function __construct(FileService $fileService, DetailRepository $repository)
    {
        $this->fileService = $fileService;
        $this->repository = $repository;
        $this->fileService->setFileDirectory('storage/details/');
    }

    public function createDetail(DetailCreateRequest $request): Detail
    {
        $data = $request->validated();
        $this->fileService->setPrefix(date('Y-m-d_H-i-s'));

        if ($request->has('image')) {
            $data['image'] = $this->fileService->uploadFile($request->image);
        }

        if ($request->has('stl')) {
            $data['stl'] = $this->fileService->uploadFile($request->stl);
        }

        if (!$request->has('nfc_code')) {
            $data['nfc_code'] = $this->generateNfcCode();
        }

        return $this->repository->create($data);
    }

    public function updateDetail(DetailUpdateRequest $request, int $id): Detail
    {
        $data = $request->validated();
        $this->fileService->setPrefix(date('Y-m-d_H-i-s'));
        
        if (!$detail = $this->repository->getById($id)) {
            throw new EntityNotFoundException();
        }

        if ($request->has('image')) {
            $data['image'] = $this->fileService->uploadFile($request->image);
            $this->fileService->deleteFile($detail->image);
        }

        if ($request->has('stl')) {
            $data['stl'] = $this->fileService->uploadFile($request->stl);
            $this->fileService->deleteFile($detail->stl);
        }

        return $this->repository->update($id, $data);
    }

    public function deleteDetail(int $id)
    {
        if (!$detail = $this->repository->getById($id)) {
            throw new EntityNotFoundException();
        }
        
        if ($detail->image) {
            $this->fileService->deleteFile($detail->image);
        }

        if ($detail->stl) {
            $this->fileService->deleteFile($detail->stl);
        }

        $this->repository->delete($id);
    }

    public function generateNfcCode(): string
    {
        $nfcCode = strtoupper(Str::random(10));
        $existedCodes = $this->repository->model()
            ->where('nfc_code', $nfcCode)
            ->pluck('nfc_code')
            ->toArray();

        if (!in_array($nfcCode, $existedCodes)) {
            return $nfcCode;
        }

        return $this->generateNfcCode();
    }
}