<?php namespace App\Reports;

use App\Reports\BaseReport;
use Carbon\Carbon;

class StaffPerformanceReport extends BaseReport {

	public $title = 'Staff Performance';

	public $description = '';

	protected $variables = [
		[	
			'name' => 'range',
			'display' => 'Report Range',
			'header' => true,
			'type' => 'daterange',
			'default' => [
				'start' => ['now', 'startOfWeek', 'subDay', 'startOfDay'],
				'end' => ['now', 'endOfWeek', 'subDay', 'endOfDay']
			]
		]
	];

	protected $sql = "SELECT
			u.display_name AS Name,
		(
		SELECT COALESCE(SUM(ta.time_spent),0)
		FROM ticket_actions AS ta
		WHERE ta.created_at BETWEEN {{ range_start }} AND {{ range_end }} AND ta.user_id = u.id
		) AS Hours,
		(
		SELECT COUNT(DISTINCT(ta.ticket_id))
		FROM ticket_actions ta
		WHERE ta.created_at BETWEEN {{ range_start }} AND {{ range_end }} AND ta.user_id = u.id
		) AS Worked, 
		IFNULL(FORMAT((SELECT Hours)/(SELECT Worked), 2), 0.00) AS 'Hours/Ticket',
		(
		SELECT COALESCE(COUNT('id'),0)
		FROM tickets t
		WHERE 
			t.created_at BETWEEN {{ range_start }} AND 
			{{ range_end }} AND t.staff_id = s.id
		) AS Created,
		(
		SELECT COALESCE(COUNT('id'),0)
		FROM tickets t
		WHERE 
			t.closed_at BETWEEN {{ range_start }} AND 
			{{ range_end }} 
		AND t.staff_id = s.id
		AND t.status = 'closed'
		) AS Closed,
		(
		SELECT COALESCE(COUNT('id'),0)
		FROM tickets t
		WHERE 
			t.closed_at BETWEEN {{ range_start }} AND 
			{{ range_end }} 
		AND t.staff_id = s.id
		AND t.status = 'resolved'
		) AS Resolved,
		(
		SELECT COALESCE(COUNT('id'),0)
		FROM tickets t
		WHERE 
			t.created_at <= {{ range_end }}
		AND  (t.closed_at >= {{ range_end }} OR t.closed_at IS NULL)
		AND t.staff_id = s.id
		) AS 'Open/New'
		FROM 
			staff s
		LEFT JOIN 
			users u ON 
			(s.user_id = u.id)";
}
