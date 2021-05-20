<?php

namespace App\Controller;

use App\AutoMapping;
use App\Request\UserProfileCreateRequest;
use App\Request\UserProfileUpdateRequest;
use App\Request\UserRegisterRequest;
use App\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends BaseController
{
    private $autoMapping;
    private $validator;
    private $userService;

    public function __construct(SerializerInterface $serializer, AutoMapping $autoMapping, ValidatorInterface $validator, UserService $userService)
    {
        parent::__construct($serializer);
        $this->autoMapping = $autoMapping;
        $this->validator = $validator;
        $this->userService = $userService;
    }

    /**
     * @Route("api/user", name="userRegister", methods={"POST"})
     * 
     * @OA\RequestBody(
     *      description="Creates user and profile at the same time",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="userID"),
     *          @OA\Property(type="string", property="password"),
     *          @OA\Property(type="string", property="userName"),
     *          @OA\Property(type="string", property="email")
     *      )
     * )
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns the new user's role",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="status_code"),
     *          @OA\Property(type="string", property="msg"),
     *          @OA\Property(type="object", property="Data",
     *                  @OA\Property(type="array", property="roles",
     *                      @OA\Items(example="user")),
     *                  @OA\Property(type="object", property="createdAt")
     *          )
     *      )
     * )
     * 
     * @OA\Tag(name="UserProfile")
     */
    public function userRegister(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class,UserRegisterRequest::class,(object)$data);

        $violations = $this->validator->validate($request);
        if (\count($violations) > 0) {
            $violationsString = (string) $violations;

            return new JsonResponse($violationsString, Response::HTTP_OK);
        }

        $response = $this->userService->userRegister($request);

        return $this->response($response, self::CREATE);
    }

    // /**
    //  * @Route("/userprofile", name="userProfileCreate", methods={"POST"})
    //  * @param Request $request
    //  * @return JsonResponse
    //  */
    // public function userProfileCreate(Request $request)
    // {
    //     $data = json_decode($request->getContent(), true);

    //     $request = $this->autoMapping->map(stdClass::class,UserProfileCreateRequest::class,(object)$data);

    //     $request->setUserID($this->getUserId());

    //     $violations = $this->validator->validate($request);
    //     if (\count($violations) > 0) {
    //         $violationsString = (string) $violations;

    //         return new JsonResponse($violationsString, Response::HTTP_OK);
    //     }

    //     $response = $this->userService->userProfileCreate($request);

    //     return $this->response($response, self::CREATE);
    // }

    /**
     * @Route("api/userprofile", name="updateUserProfile", methods={"PUT"})
     * 
     * @OA\Tag(name="UserProfile")
     * 
     * @OA\Parameter(
     *      name="token",
     *      in="header",
     *      description="token to be passed as a header",
     *      required=true 
     * )
     * 
     * @OA\RequestBody(
     *      description="Updates the profile of the signed-in user",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="userName"),
     *          @OA\Property(type="string", property="city"),
     *          @OA\Property(type="string", property="story"),
     *          @OA\Property(type="string", property="image")
     *      )
     * )
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns the updated profile of the user",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="status_code"),
     *          @OA\Property(type="string", property="msg"),
     *          @OA\Property(type="object", property="Data",
     *                  @OA\Property(type="string", property="userName"),
     *                  @OA\Property(type="string", property="city"),
     *                  @OA\Property(type="string", property="story"),
     *                  @OA\Property(type="string", property="image"),
     *                  @OA\Property(type="object", property="date"),
     *                  @OA\Property(type="object", property="dateAndTime")
     *          )
     *      )
     * )
     * 
     * @Security(name="Bearer")
     */
    public function updateUserProfile(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $request = $this->autoMapping->map(stdClass::class, UserProfileUpdateRequest::class, (object)$data);
        
        $request->setUserID($this->getUserId());

        $response = $this->userService->userProfileUpdate($request);

        return $this->response($response, self::UPDATE);
    }

    /**
     * @Route("api/userprofile", name="getUserProfileByID",methods={"GET"})
     * 
     * @OA\Tag(name="UserProfile")
     * 
     * @OA\Parameter(
     *      name="token",
     *      in="header",
     *      description="token to be passed as a header",
     *      required=true 
     * )
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns the profile of the signed-in user",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="status_code"),
     *          @OA\Property(type="string", property="msg"),
     *          @OA\Property(type="object", property="Data",
     *                  @OA\Property(type="string", property="userName"),
     *                  @OA\Property(type="string", property="city"),
     *                  @OA\Property(type="string", property="story"),
     *                  @OA\Property(type="string", property="image"),
     *                  @OA\Property(type="object", property="date"),
     *                  @OA\Property(type="object", property="dateAndTime")
     *          )
     *      )
     * )
     * 
     * @Security(name="Bearer")
     */
    public function getUserProfileByID()
    {
        $response = $this->userService->getUserProfileByUserID($this->getUserId());

        return $this->response($response,self::FETCH);
    }

    /**
     * @Route("api/userprofileall", name="userProfileAll", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns array of all profiles",
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="status_code"),
     *          @OA\Property(type="string", property="msg"),
     *          @OA\Property(type="array", property="Data",
     *              @OA\Items(
     *                  @OA\Property(type="string", property="userName"),
     *                  @OA\Property(type="string", property="city"),
     *                  @OA\Property(type="string", property="story"),
     *                  @OA\Property(type="string", property="image"),
     *                  @OA\Property(type="object", property="date"),
     *                  @OA\Property(type="object", property="dateAndTime")
     *              )
     *          )
     *      )
     * )
     * 
     * @OA\Tag(name="UserProfile")
     * 
     */
    public function userProfileAll()
    {
        $response = $this->userService->getAllProfiles();

        return $this->response($response,self::FETCH);
    }
}
