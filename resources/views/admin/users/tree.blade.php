@extends('admin.layouts.master')

@section('content')
<div class="content-wrapper">
    <x-network-tree 
        :rootUser="$rootUser" 
        :treeData="$treeData" 
        :isAdmin="true" 
        :searchRoute="route('admin.users.tree')" 
        :subtreeRoutePattern="route('admin.users.tree', 'PLACEHOLDER')" 
    />
</div>
@endsection