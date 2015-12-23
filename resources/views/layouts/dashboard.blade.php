<div class="row">
@foreach($itens as $item)
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-{{$item['class']}}">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-{{$item['icon']}} fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$item['count']}}</div>
                        <div>{{$item['text']}}</div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">{!! link_to_route($item['route'], 'Ver Detalhes')!!}</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
@endforeach
</div>