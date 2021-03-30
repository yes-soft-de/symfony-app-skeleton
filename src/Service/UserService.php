<?php


namespace App\Service;


use App\AutoMapping;
use App\Entity\UserEntity;
use App\Entity\UserProfileEntity;
use App\Manager\UserManager;
use App\Request\UserProfileCreateRequest;
use App\Request\UserProfileUpdateRequest;
use App\Request\UserRegisterRequest;
use App\Response\UserProfileCreateResponse;
use App\Response\UserProfileResponse;
use App\Response\UserRegisterResponse;

class UserService
{
    private $autoMapping;
    private $userManager;

    public function __construct(AutoMapping $autoMapping, UserManager $userManager)
    {
        $this->autoMapping = $autoMapping;
        $this->userManager = $userManager;
    }

    public function userRegister(UserRegisterRequest $request)
    {
        $userRegister = $this->userManager->userRegister($request);

        if ($userRegister instanceof UserEntity) 
        {
            return $this->autoMapping->map(UserEntity::class, UserRegisterResponse::class, $userRegister);
        }
        elseif ($userRegister == true) 
        {  
            $user = $this->userManager->getUserByUserID($request->getUserID());

            $user['found']="yes";

            return $user;
        }


    }

    // public function userProfileCreate(UserProfileCreateRequest $request)
    // {
    //     $userProfile = $this->userManager->userProfileCreate($request);

    //     if ($userProfile instanceof UserProfileEntity) {

    //         return $this->autoMapping->map(UserProfileEntity::class,UserProfileCreateResponse::class, $userProfile);
    //     }
    //     elseif ($userProfile == true) 
    //     {
    //         $user = $this->getUserProfileByUserID($request->getUserID());
    //         return $user;
    //     }
    // }

    public function userProfileUpdate(UserProfileUpdateRequest $request)
    {
        $item = $this->userManager->userProfileUpdate($request);

        return $this->autoMapping->map(UserProfileEntity::class,UserProfileResponse::class, $item);
    }

    public function getUserProfileByUserID($userID)
    {
        $item = $this->userManager->getProfileByUserID($userID);

        return $this->autoMapping->map('array', UserProfileResponse::class, $item);

    }

    public function getAllProfiles()
    {
        $response = [];
        $result = $this->userManager->getAllProfiles();

        foreach ($result as $row)
        {
            $response[] = $this->autoMapping->map(UserProfileEntity::class, UserProfileResponse::class, $row);
        }
        return $response;
    }
}