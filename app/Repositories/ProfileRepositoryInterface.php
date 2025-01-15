<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ProfileRepository;
interface ProfileRepositoryInterface {
    public function getProfile($user);
    public function updateProfile($id, array $data);
    public function deleteProfile($id);
} 