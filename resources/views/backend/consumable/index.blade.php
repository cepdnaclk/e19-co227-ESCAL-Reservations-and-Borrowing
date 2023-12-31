@extends('backend.layouts.app')

@section('title', __('equipment'))

@section('breadcrumb-links')
    @include('backend.equipment.includes.breadcrumb-links')
@endsection

@section('content')
    <div>
        <x-backend.card>
            <x-slot name="header">
                Consumables
            </x-slot>

            <x-slot name="body">
                <a class="btn btn-secondary btn-150 mb-2" href="{{ route('admin.consumable.items.index') }}">Items</a>
                <br/>
                <a class="btn btn-secondary btn-150 mb-2" href="{{ route('admin.consumable.types.index') }}">Types</a>
            </x-slot>
        </x-backend.card>
    </div>
@endsection
