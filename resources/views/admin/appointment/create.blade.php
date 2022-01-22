@extends('admin.layouts.master')

@section('title')
    Appointment
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
                    <li class="breadcrumb-item"><a href="#">Doctor</a></li>
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

    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{$error}}
        </div>
    @endforeach

    <form action="{{route('appointment.store')}}" method="post">
        @csrf
        <div class="card">
            <div class="card-header">
                Choose date
            </div>
            <div class="card-body">
                <input type="text" class="form-control datetimepicker-input"  id="datepicker" data-toggle="datetimepicker" data-target="#datepicker" name="date">
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Choose AM time
                <span style="margin-left: 700px">Check/Uncheck
                    <input type="checkbox" onclick=" for(c in document.querySelectorAll('.table_1 .time_input')) document.querySelectorAll('.table_1 .time_input').item(c).checked=this.checked" >
                </span>
            </div>
            <div class="card-body">
                <table class="table table-striped table_1">
                    <tbody>
                        {!! $amHtml  !!}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Choose PM time
                <span style="margin-left: 700px">Check/Uncheck
                    <input type="checkbox" onclick=" for(c in document.querySelectorAll('.table_2 .time_input')) document.querySelectorAll('.table_2 .time_input').item(c).checked=this.checked" >
                </span>
            </div>
            <div class="card-body">

                <table class="table table-striped table_2">
                    <tbody>
                    {!! $pmHtml  !!}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

</div>

@endsection
