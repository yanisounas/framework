<?php

namespace Framework\Controller;

use Framework\AbstractClass\ApiBase;
use Framework\API\Attributes\API;
use Framework\ORM\Mapper;
use Framework\Request\Request;
use Framework\Response\JSONResponse;
use Framework\Response\Response;
use Framework\Router\Attributes\Route;
use Framework\Security\Data;
use ReflectionException;

#[API(appName: "URLShortener")]
class APIController extends ApiBase
{
    public function __construct(private readonly Mapper $mapper = new Mapper()) {}

    /**
     * @throws ReflectionException
     */
    #[Route("/api/new", method: "POST|GET")]
    public function saveUrl(): JSONResponse
    {

        $request = Request::getFirst();

        if (empty($request) || !$request["key"] || !$request["url"])
        {
            $missing = [];
            if (!$request["key"])
                $missing[] = "key";

            if (!$request["url"])
                $missing[] = "url";

            return $this->json(["success" => false, "error" => "Missing parameters check the doc! http://localhost:8000/api-documentation", "missing" => $missing], Response::BAD_REQUEST);
        }
        $key = Data::cleanString($request["key"]);
        $url = Data::cleanString($request["url"]);

        if (!$user = $this->mapper->getBy("User", ["api_key" => $key]))
            return $this->json(["success" => false, "error" => "Key $key not found!"]);

        try
        {
            $token = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), -7);
            $this->mapper->make("Url", user_id: $user->getId(), url: $url, token: $token);
            return $this->json(["success" => true, "url" => $url, "token" => $token, "shortened_link" => "http://localhost:8000/$token"], Response::CREATED);
        }catch (\Exception $e)
        {
            return $this->json(["success" => false, "error" =>  "(". $e->getCode() . ") - Error when saving the url. Please contact an administrator."], Response::INTERNAL_SERVER_ERROR);
        }


    }

    #[Route("/api/delete")]
    public function deleteUrl(): JSONResponse
    {
        $request = Request::get();

        if (empty($request) || !$request["key"] || !$request["token"])
        {
            $missing = [];
            if (!$request["key"])
                $missing[] = "key";

            if (!$request["url"])
                $missing[] = "url";

            return $this->json(["success" => false, "error" => "Missing parameters check the doc! http://localhost:8000/api-documentation", "missing" => $missing], Response::BAD_REQUEST);
        }

        $key = Data::cleanString($request["key"]);
        $token = Data::cleanString($request["token"]);

        if (!$user = $this->mapper->getBy("User", ["api_key" => $key]))
            return $this->json(["success" => false, "error" => "Key $key not found!"]);

        if (!$url = $this->mapper->getBy("Url", ["token" => $token, "user_id" => $user->getId()]))
            return $this->json(["success" => false, "error" => "Token $token not found for key $key!"]);

        try
        {
            $this->mapper->delete("Url", id: $url->getId());
            return $this->json(["success" => true]);
        }catch (\Exception $e)
        {
            return $this->json(["success" => false, "error" =>  "(". $e->getCode() . ") - Error when saving the url. Please contact an administrator."], Response::INTERNAL_SERVER_ERROR);
        }
    }
}