<?php

namespace SimPay\Laravel\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use SimPay\Laravel\Exceptions\AuthorizationException;
use SimPay\Laravel\Exceptions\ResourceNotFoundException;
use SimPay\Laravel\Exceptions\SimPayException;
use SimPay\Laravel\Exceptions\ValidationFailedException;
use SimPay\Laravel\SimPay;

abstract class Service
{
    /**
     * @throws SimPayException
     * @throws ValidationFailedException
     * @throws AuthorizationException
     */
    protected function sendRequest(string $uri, string $method, array $options = [], bool $checkHeaders = true): PromiseInterface|Response
    {
        try {
            $request = Http::withToken(config('simpay.bearer_token'))->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-SIM-VERSION' => SimPay::VERSION,
                'X-SIM-PLATFORM' => 'PHP-LARAVEL',
            ])->send($method, sprintf('%s/%s', config('simpay.api_url', 'https://api.simpay.pl') , $uri), $options);
        } catch (ConnectionException $exception) {
            throw new SimPayException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }

        if(!$checkHeaders) {
            return $request;
        }

        if ($request->successful()) {
            return $request;
        }

        if ($request->forbidden() || $request->unauthorized()) {
            throw new AuthorizationException($request->json('message', $request->json('errorCode')), errorCode: $request->json('errorCode'));
        }

        if ($request->unprocessableContent()) {
            throw new ValidationFailedException($request->json('errors'), $request->json('message'));
        }

        if($request->notFound()) {
            throw new ResourceNotFoundException($request->json('message'), errorCode: $request->json('errorCode'));
        }

        throw new SimPayException('API ERROR '. $request->status() . ': ' . $request->json('message', $request->body()), errorCode: $request->json('errorCode'));
    }
}
