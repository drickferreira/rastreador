@extends('layouts.master')

@section('content')
    <script type="text/javascript">
        var centreGot = false;
    </script>
    @include('layouts.navbar')
    @include('layouts.sidebar')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        {!! $map['html'] !!}
    </div>     
@endsection

@section('custom-js')
    {!! $map['js'] !!}
@endsection