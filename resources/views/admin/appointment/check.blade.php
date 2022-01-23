<form action="{{ route('appointment.update')}} " method="post">
    @csrf
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
                    <input type="hidden" name="appoinmentId" value="{{$appointmentId}}">
                    {!! $amHtml !!}
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
                    {!! $pmHtml !!}
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
