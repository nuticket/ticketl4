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
						<span class="time"><i class="fa fa-clock-o"></i> {{ $ticket['created_at']->format('g:i a') }}</span>
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
				<li id="action-{{ $action['id'] }}">
					<i class="fa fa-desktop bg-blue"></i>
					<div class="timeline-item">
						<ul class="list-inline time">
							@if ($action['time_spent'] > 0)
							<li><span  data-toggle="tooltip" title="{{ $action['time_spent'] }} hour(s) worked"><i class="fa fa-wrench"></i> {{ $action['time_spent'] }}</span></li>
							@endif
							<li><i class="fa fa-clock-o"></i> {{ $action['created_at']->format('g:i a') }}</li>
						</ul>
						<h3 class="timeline-header{{ $action['message'] == null ? ' no-border' : '' }}">
							<a href="#">{{ $action['user']['display_name'] }}</a> 
							@if ($action['type'] == 'reply')
							replied to ticket
							@elseif ($action['type'] == 'comment')
							commented on ticket
							@elseif (in_array($action['type'], ['closed', 'resolved']))
							{{ $action['type'] }}d the ticket
							@elseif ($action['type'] == 'edit')
							edited ticket
							@elseif ($action['type'] == 'transfer')
							transfered ticket to {{ $action['transfer']['name']}}
							@elseif ($action['type'] == 'assign')
							assigned ticket to {{ $action['assigned']['user']['display_name']}}
							@endif
						</h3>
						@if($action['message'] != null)
						<div class="timeline-body">
							{{ $action['message'] }}
						</div>
						@endif
					</div>
				</li>
				@endforeach


				<!-- END timeline item -->
				<li>
					<i class="fa fa-clock-o"></i>
				</li>
			</ul>
			<div class="nav-tabs-custom" id="action">
				<ul class="nav nav-tabs">
					<!-- <li class="active"><a href="#quick" data-toggle="tab">Quick</a></li> -->
					<li{{ Session::get('type') == null || Session::get('type') == 'reply' ? ' class="active"' : '' }}><a href="#reply" data-toggle="tab">Reply {{ Session::get('type') }}</a></li>
					<li{{ Session::get('type') == 'comment' ? ' class="active"' : '' }}><a href="#comment" data-toggle="tab">Comment</a></li>
					<li{{ Session::get('type') == 'transfer' ? ' class="active"' : '' }}><a href="#transfer" data-toggle="tab">Dept Transfer</a></li>
					<li{{ Session::get('type') == 'assign' ? ' class="active"' : '' }}><a href="#assign" data-toggle="tab">Assign</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane{{ Session::get('type') == null || Session::get('type') == 'reply' ? ' active' : '' }}" id="reply">
						{{ Form::open(['route' => ['actions.store', 'reply'], 'class' => 'form-horizontal']) }}
						<input name="ticket_id" type="hidden" value="{{ $ticket['id'] }}">
						<div class="form-group{{ $errors->has('reply_message') ? ' has-error' : null }}">
							<div class="col-md-12">
								@if ($errors->has('reply_message') || $errors->has('reply_hrs'))
								<ul class="list-unstyled">
								@foreach ($errors->all() as $error)
									<li class="text-red"><strong>{{ $error }}</strong></li>
								@endforeach
								</ul>
								@endif
								<textarea class="textarea form-control" name="reply_message" placeholder="Enter a response here" style="height: 100px;">{{ Input::old('reply_message') }}</textarea>

							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label" for="textinput">Status</label>  
									<div class="col-md-8">
										{{ Form::select('reply_status', ['open' => 'Open', 'closed' => 'Close', 'resolved' => 'Resolve'], (Input::old('reply_status') !== null ? Input::old('status') :  $ticket['status']), ['class' => 'form-control input-sm']) }}
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group{{ $errors->has('reply_hrs') ? ' has-error' : null }}">
									<label class="col-md-6 control-label" for="textinput">Worked Hours</label>  
									<div class="col-md-6">
										<input id="textinput" name="reply_hrs" type="text" value="{{ Input::old('reply_hrs') }}" class="form-control input-sm">
									</div>
								</div>
							</div>
							<div class="col-md-4">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
										<input class="btn btn-primary btn-sm" type="submit" name="action_reply" value="Post Reply">
							</div>
						</div>
						</form>
					</div>
					<div class="tab-pane{{ Session::get('type') == 'comment' ? ' active' : '' }}" id="comment">
						{{ Form::open(['route' => ['actions.store', 'comment'], 'class' => 'form-horizontal']) }}
						<input name="ticket_id" type="hidden" value="{{ $ticket['id'] }}">
						<div class="form-group{{ $errors->has('comment_message') ? ' has-error' : null }}">
							<div class="col-md-12">
								@if ($errors->has('comment_message') || $errors->has('comment_hrs'))
								<ul class="list-unstyled">
								@foreach ($errors->all() as $error)
									<li class="text-red"><strong>{{ $error }}</strong></li>
								@endforeach
								</ul>
								@endif
								<textarea class="textarea form-control" name="comment_message" placeholder="Enter a internal comment here" style="height: 100px;">{{ Input::old('comment_message') }}</textarea>

							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								
								<div class="form-group{{ $errors->has('comment_hrs') ? ' has-error' : null }}">
									<label class="col-md-6 control-label" for="textinput">Worked Hours</label>  
									<div class="col-md-6">
										<input id="textinput" name="comment_hrs" type="text" value="{{ Input::old('comment_hrs') }}" class="form-control input-sm">
									</div>
								</div>
							</div>
							<div class="col-md-4">
							</div>
							<div class="col-md-4">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
										<input class="btn btn-primary btn-sm" type="submit" name="action_reply" value="Post Comment">
							</div>
						</div>
						</form>
					</div>
					<div class="tab-pane{{ Session::get('type') == 'transfer' ? ' active' : '' }}" id="transfer">
					{{ Form::open(['route' => ['actions.store', 'transfer'], 'class' => 'form-horizontal']) }}
						<input name="ticket_id" type="hidden" value="{{ $ticket['id'] }}">
						<div class="form-group">
							<div class="col-md-6">
								@if ($errors->has('transfer_message'))
								<ul class="list-unstyled">
								@foreach ($errors->all() as $error)
									<li class="text-red"><strong>{{ $error }}</strong></li>
								@endforeach
								</ul>
								@endif
								{{ Form::select('transfer_id', $depts, $ticket->ticket_dept_id, ['class' => 'default-select form-control']); }}

							</div>
						</div>
						<div class="form-group{{ $errors->has('transfer_message') ? ' has-error' : null }}">
							<div class="col-md-12">
								<textarea class="textarea form-control" name="transfer_message" placeholder="Enter reasons for the transfer" style="height: 100px;">{{ Input::old('transfer_message') }}</textarea>

							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
										<input class="btn btn-primary btn-sm" type="submit" name="action_reply" value="Transfer">
							</div>
						</div>
						</form>
					</div>
					<div class="tab-pane{{ Session::get('type') == 'assign' ? ' active' : '' }}" id="assign">
						{{ Form::open(['route' => ['actions.store', 'assign'], 'class' => 'form-horizontal']) }}
						<input name="ticket_id" type="hidden" value="{{ $ticket['id'] }}">
						<div class="form-group">
							<div class="col-md-6">
								@if ($errors->has('assign_message'))
								<ul class="list-unstyled">
								@foreach ($errors->all() as $error)
									<li class="text-red"><strong>{{ $error }}</strong></li>
								@endforeach
								</ul>
								@endif
								{{ Form::select('assigned_id', array_except(array_replace([0 => 'Nobody'], $staff), $ticket->staff_id), Auth::user()->staff->id, ['class' => 'default-select form-control']); }}

							</div>
						</div>
						<div class="form-group{{ $errors->has('assign_message') ? ' has-error' : null }}">
							<div class="col-md-12">
								<textarea class="textarea form-control" name="assign_message" placeholder="Enter reasons for the assignment or instructions for assignee" style="height: 100px;">{{ Input::old('assign_message') }}</textarea>

							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input class="btn btn-primary btn-sm" type="submit" name="action_reply" value="Assign">
							</div>
						</div>
						</form>
					</div>
				</div><!-- /.tab-content -->
			</div>
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
									<dd>{{ $ticket['time_spent'] }}</dd>
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