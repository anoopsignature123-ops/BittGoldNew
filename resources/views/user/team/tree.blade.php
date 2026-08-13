@extends('user.layouts.master')

@section('content')
<div class="content-wrapper">
    <x-network-tree 
        :rootUser="$rootUser" 
        :treeData="$treeData" 
        :isAdmin="false" 
        :searchRoute="route('user.team.tree')" 
        :subtreeRoutePattern="route('user.team.tree', 'PLACEHOLDER')" 
    />
</div>
@endsection