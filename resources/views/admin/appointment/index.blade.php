@extends('admin.layouts.master')

@section('title')
    Appointment Page
@endsection

@section('content')

<div class="page-header">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="ik ik-command bg-blue"></i>
                <div class="d-inline">
                    <h5>Doctors</h5>
                    <span>Appoinment Time</span>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <nav class="breadcrumb-container" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/"><i class="ik ik-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.index') }}">Doctor</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Appointment</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container">
    @if(Session::has('message'))
        <div class="alert bg-success alert-success text-white" role="alert">
            {{Session::get('message')}}
        </div>
    @endif

    @if( Session::has('errmessage') )
        <div class="alert bg-danger alert-success text-white" role="alert">
            {{Session::get('errmessage')}}
        </div>
    @endif

    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{$error}}
        </div>
    @endforeach

    <form action="{{route('appointment.check')}}" method="post">
        @csrf
        <div class="card">
            <div class="card-header">
                Choose date
                <br>
                @if(isset($date))
                    Your timetable for:
                    {{$date}}
                @endif
            </div>
            <div class="card-body">
                <input type="text" class="form-control datetimepicker-input" id="datepicker" data-toggle="datetimepicker" data-target="#datepicker" name="date" >
                <br>
                <button type="submit" class="btn btn-primary">Check</button>
            </div>
        </div>
    </form>

    @if( Route::is('appointment.check') )
        @include('admin.appointment.check')
    @else
        <h3>Your Appoinment Time List: {{$myappointments->count()}}</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Creator</th>
                    <th scope="col">Date</th>
                    <th scope="col">View/Update</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $myappointments as $appoinment )
                    <tr>
                        <th scope="row"></th>
                        <td>{{$appoinment->doctor->name}}</td>
                        <td>{{$appoinment->date}}</td>
                        <td>
                            <form action="{{route('appointment.check')}}" method="post">
                                @csrf
                                <input type="hidden" name="date" value="{{$appoinment->date}}">
                                <button type="submit" class="btn btn-primary">View/Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
