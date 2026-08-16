@extends('components.dashboard-layout')

@section('title', 'Add Employee')

@section('content')
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-semibold">Add Employee</h1>
        
        <div class="row">
            <div class="col-12">
                <table class="">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="name" placeholder="Name" required></td>
                            <td><input type="email" name="email" placeholder="Email" required></td>
                            <td><input type="password" name="password" placeholder="Password" required></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

