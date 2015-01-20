@extends('layouts.master')

@section('content')
<section class="content-header">
	<h1>
		Tickets
		<small>Control panel</small>
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
	<div class="mailbox row">
		<div class="col-xs-12">
			<div class="box box-solid">
				<div class="box-body">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="row pad">
								<div class="col-sm-6">
									<div class="btn-group">
										<a href="{{ route('tickets.list', ['status' => 'new-open']) }}" class="btn btn-default btn-flat btn-sm active">Open ({{ $open_count }})</a>
										<a href="{{ route('tickets.list', ['status' => 'closed']) }}" class="btn btn-default btn-flat btn-sm">Closed ({{ $close_count }})</a>
										@if($is_staff)
										<a href="{{ route('tickets.list', ['staff_id' => user('staff')->id, 'status' => 'new-open']) }}" class="btn btn-default btn-flat btn-sm">Assigned ({{ $assigned_count }})</a>
										@endif
									</div>

								</div>
								<div class="col-sm-6 search-form">
									<form action="{{ route('tickets.list') }}" class="text-right form-inline">
										<div class="input-group">
											<input type="text" name="q" value="{{ isset($query['q']) ? $query['q'] : null }}" class="form-control input-sm" placeholder="Search">
											<input type="hidden" name="status[]" value="new">
											<input type="hidden" name="status[]" value="open">
											<input type="hidden" name="status[]" value="closed">
											<div class="input-group-btn">
												<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
											</div>
										</div>
										<div class="form-group">
											<a href="#" class="form-control-static" data-toggle="modal" data-target="#compose-modal">advanced</a>
										</div>
									</form>
								</div>
							</div><!-- /.row -->

							<div class="box-body table-responsive">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th><a href="{{ sort_url('id') }}">Id<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('id', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('last_action_at') }}">Date<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('last_action_at', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('subject') }}">Subject<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('subject', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('user') }}">From<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('user', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('priority') }}">Priority<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('priority', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('staff') }}">Assigned<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('staff', null, '-') }}"></i></span></a></th>
										</tr>
									</thead>
									
									<tbody>
										@if ($tickets->count() >= 1)
										@foreach ($tickets as $ticket)
										<tr>
											<td>{{ link_to_route('tickets.show', $ticket['id'], [$ticket['id']]) }}</td>
											<td>{{ datetime($ticket['last_action_at']) }}</td>
											<td>{{ link_to_route('tickets.show', $ticket['subject'], [$ticket['id']]) }}</td>
											<td>{{ $ticket['user'] }}</td>
											<td>{{ $ticket['priority'] }}</td>
											<td>{{ $ticket['staff'] }}</td>
										</tr>
										@endforeach
									</tbody>
										<tfoot>
											<tr>
												<th>Id</th>
												<th>Date</th>
												<th>Subject</th>
												<th>From</th>
												<th>Priority</th>
												<th>Assigned</th>
											</tr>
										</tfoot>
										@else
										<tr>
											<td colspan="6" class="text-center">There are no tickets to view</td>
										</tr>
									</tbody>
										@endif
									
								</table>
								<div class="row">
									<div class="col-xs-4">
										<div class="table_info" id="example2_info">Showing {{ $tickets->getFrom() }} to {{ $tickets->getTo() }} of {{ $tickets->getTotal() }} tickets</div>
									</div>
									<div class="col-xs-8">
										<div class="table_paginate paging_bootstrap">
											{{ $tickets->appends($query)->links() }}
										</div>
									</div>
								</div>
							</div><!-- /.col (RIGHT) -->
						</div><!-- /.row -->
					</div><!-- /.box-body -->
				</div><!-- /.box -->
			</div><!-- /.col (MAIN) -->
		</div>

		<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-envelope-o"></i> Compose New Message</h4>
                    </div>
                    <form action="#" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">TO:</span>
                                    <input name="email_to" type="email" class="form-control" placeholder="Email TO">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">CC:</span>
                                    <input name="email_to" type="email" class="form-control" placeholder="Email CC">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">BCC:</span>
                                    <input name="email_to" type="email" class="form-control" placeholder="Email BCC">
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea name="message" id="email_message" class="form-control" placeholder="Message" style="height: 120px;"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="btn btn-success btn-file">
                                    <i class="fa fa-paperclip"></i> Attachment
                                    <input type="file" name="attachment"/>
                                </div>
                                <p class="help-block">Max. 32MB</p>
                            </div>

                        </div>
                        <div class="modal-footer clearfix">

                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>

                            <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-envelope"></i> Send Message</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
		@stop