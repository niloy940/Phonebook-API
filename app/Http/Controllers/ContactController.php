<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactCollection;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('client')) {
            return ContactCollection::collection(auth()->user()->contacts);
        } elseif (auth()->user()->hasRole('admin')) {
            return ContactCollection::collection(Contact::all());
        } else {
            return 'unauthorized';
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactRequest $request)
    {
        $contact = auth()->user()->contacts()->create($request->all());

        return response([
            'data' => new ContactCollection($contact)
        ], Response::HTTP_CREATED);
    }
    
    public function bulkInsert()
    {
        $data = [
            ['name' => 'John', 'phone_number' => '789896785', 'user_id' => auth()->user()->id],
            ['name' => 'Maria', 'phone_number' => '6788787865', 'user_id' => auth()->user()->id],
            ['name' => 'Julia', 'phone_number' => '678756445', 'user_id' => auth()->user()->id],
        ];


        $this->insert($data);
    }

    public function insert($data)
    {
        Contact::insert($data);

        return ContactCollection::collection($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        if (auth()->user()->hasRole('client')) {
            $this->authorize('update', $contact);

            return new ContactCollection($contact);
        } elseif (auth()->user()->hasRole('admin')) {
            return new ContactCollection($contact);
        } else {
            return 'unauthorized';
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContactRequest  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        if (auth()->user()->hasRole('client')) {
            $this->authorize('update', $contact);

            $contact->update($request->all());

            return new ContactCollection($contact);
        } elseif (auth()->user()->hasRole('admin')) {
            $contact->update($request->all());
            
            return new ContactCollection($contact);
        } else {
            return 'unauthorized';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        if (auth()->user()->hasRole('client')) {
            $this->authorize('update', $contact);

            $contact->delete();

            return new ContactCollection($contact);
        } elseif (auth()->user()->hasRole('admin')) {
            $contact->delete();
            
            return new ContactCollection($contact);
        } else {
            return 'unauthorized';
        }
    }
}
