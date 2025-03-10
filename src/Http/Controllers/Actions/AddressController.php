<?php

namespace StatamicRadPack\Shopify\Http\Controllers\Actions;

use Illuminate\Http\Request;
use Shopify\Clients\Rest;
use Statamic\Facades\User;
use Statamic\Support\Arr;
use StatamicRadPack\Shopify\Http\Requests;

class AddressController extends BaseActionController
{
    public function create(Requests\CreateOrUpdateAddressRequest $request)
    {
        $customerId = request()->input('customer_id') ?? User::current()?->get('shopify_id') ?? false;

        if (! $customerId) {
            return $this->withErrors($request, __('No customer_id to associate the address with'));
        }

        $validatedData = $request->validated();

        try {

            $response = app(Rest::class)->post(path: 'customers/'.$customerId.'/addresses', body: $validatedData);

            if ($response->getStatusCode() == 201) {
                $data = Arr::get($response->getDecodedBody(), 'customer_address', []);

                return $this->withSuccess($request, [
                    'message' => __('Address created'),
                    'address' => $data,
                ]);
            }

        } catch (\Exception $error) {
            return $this->withErrors($request, $error->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $customerId = request()->input('customer_id') ?? User::current()?->get('shopify_id') ?? false;

        if (! $customerId) {
            return $this->withErrors($request, __('No customer_id to associate the address with'));
        }

        try {

            $response = app(Rest::class)->delete(path: 'customers/'.$customerId.'/addresses/'.$id);

            if ($response->getStatusCode() == 200) {

                return $this->withSuccess($request, [
                    'message' => __('Address deleted'),
                ]);
            }

        } catch (\Exception $error) {
            return $this->withErrors($request, $error->getMessage());
        }
    }

    public function store(Requests\CreateOrUpdateAddressRequest $request, $id)
    {
        $customerId = request()->input('customer_id') ?? User::current()?->get('shopify_id') ?? false;

        if (! $customerId) {
            return $this->withErrors($request, __('No customer_id to associate the address with'));
        }

        $validatedData = $request->validated();

        try {

            $response = app(Rest::class)->put(path: 'customers/'.$customerId.'/addresses/'.$id, body: $validatedData);

            if ($response->getStatusCode() == 200) {
                $data = Arr::get($response->getDecodedBody(), 'customer_address', []);

                return $this->withSuccess($request, [
                    'message' => __('Address updated'),
                    'address' => $data,
                ]);
            }

        } catch (\Exception $error) {
            return $this->withErrors($request, $error->getMessage());
        }
    }
}
