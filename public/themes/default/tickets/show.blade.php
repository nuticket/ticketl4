@extends('layouts.master')

@section('content')
<section class="content-header">
	<h1>
		Tickets
		<small>#{{ $ticket['id'] }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">

	@if ($errors)
	<div class="alert alert-danger alert-dismissable">
		<i class="fa fa-ban"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<b>Error!</b> {{ $errors->first() }}
	</div>
	@endif
	<div class="row">
		<div class="col-md-9">
			<!-- The time line -->
			<ul class="timeline">
				<!-- timeline time label -->
				<li class="time-label">
					<span class="bg-red">{{ $ticket['created_at']->format('j M Y'); $lastday = $ticket['created_at'] }}</span>
				</li>
				<!-- /.timeline-label -->
				<!-- timeline item -->
				<li>
					<i class="fa fa-desktop bg-blue"></i>
					<div class="timeline-item">
						<span class="time"><i class="fa fa-clock-o"></i> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $ticket['created_at'])->format('g:i a') }}</span>
						<h3 class="timeline-header"><a href="#">{{ $ticket['staff']['user']['display_name'] }}</a> created a ticket for <a href="#">{{ $ticket['user']['display_name'] }}</a></h3>
						<div class="timeline-body">
							<h5>{{ $ticket['subject'] }}</h5>
							{{ $ticket['description'] }}
						</div>
						<div class='timeline-footer'>
							<a class="btn btn-primary btn-xs">Read more</a>
							<a class="btn btn-danger btn-xs">Delete</a>
						</div>

					</div>
				</li>
				@foreach ($ticket['actions'] as $action)
				@if (!isset($lastday) || !$action['created_at']->isSameDay($lastday))
				<li class="time-label">
					<span class="bg-red">{{ $action['created_at']->format('j M Y') }}</span>
				</li>
				{{-- */$lastday = $action['created_at'];/* --}}
				@endif
				<!-- /.timeline-label -->
				<!-- timeline item -->
				<li>
					<i class="fa fa-desktop bg-blue"></i>
					<div class="timeline-item">
						<span class="time"><i class="fa fa-clock-o"></i> {{ $action['created_at']->format('g:i a') }}</span>
						<h3 class="timeline-header">
							<a href="#">{{ $action['user']['display_name'] }}</a> 
							@if ($action['type'] == 'response')
							responded to ticket
							@elseif ($action['type'] == 'note')
							commented on ticket
							@elseif (in_array($action['type'], ['release', 'close']))
							{{ $action['type'] }}d the ticket
							@elseif (in_array($action['type'], ['edit', 'reassign']))
							{{ $action['type'] }}ed ticket
							@endif
						</h3>
						<div class="timeline-body">
							{{ $action['message'] }}
						</div>
						<div class='timeline-footer'>
							<a class="btn btn-primary btn-xs">Read more</a>
							<a class="btn btn-danger btn-xs">Delete</a>
							<ul class="list-inline pull-right">
								@if (($action['travel_hrs'] + $action['worked_hrs']) > 0)
								<li>
									<a href="#">
										<i class="fa fa-wrench"></i> 
										{{ $action['travel_hrs'] + $action['worked_hrs'] }}
									</a>
								</li>
								@endif
							</ul>
						</div>

					</div>
				</li>
				@endforeach
					
					
					<!-- END timeline item -->
					<li>
						<i class="fa fa-clock-o"></i>
					</li>
				</ul>
			</div>
			<div class="col-md-3">
				<!-- general form elements -->
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Details</h3>
					</div><!-- /.box-header -->
					<!-- form start -->
					<form role="form">
						<div class="box-body">

							<div class="row">
								<div class="col-xs-12">
									<dl class="dl-horizontal detail">
										<dt>Id</dt>
										<dd>{{ $ticket['id'] }}</dd>
										<dt>Status</dt>
										<dd>{{ $ticket['status'] }}</dd>
										<dt>Priority</dt>
										<dd>{{ $ticket['priority'] }}</dd>
										<dt>Department</dt>
										<dd>{{ $ticket['dept']['name'] }}</dd>
										<dt>User</dt>
										<dd><a href="#">{{ $ticket['user']['display_name'] }}</a></dd>
										<dt>Phone</dt>
										<dd></dd>
									</dl>
								</div>
							</div>
						</div><!-- /.box-body -->

						<div class="box-footer">
							<div class="row">
								<div class="col-xs-12">
									<dl class="dl-horizontal detail">
										<dt>Assigned</dt>
										<dd>{{ $ticket['staff']['user']['display_name'] }}</dd>
										<dt>Total Hours</dt>
										<dd>{{ $ticket['worked_hrs'] + $ticket['travel_hrs'] }}</dd>
										<dt>Last Action</dt>
										<dd>{{ datetime($ticket['last_action_at']) }}</dd>
									</dl>
								</div>
							</div>
						</div>
					</form>
				</div><!-- /.box -->




			</div><!--/.col (left) -->
			<!-- right column -->

		</div>
		@stop