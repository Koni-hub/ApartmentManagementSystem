<?php include ('./db_connect.php'); ?>
<style>
	.on-print{
		display: none;
	}
	.footer {
		margin-top: 50px;
		font-size: 18px;
		display: flex;
		gap: 45%;
		align-items: center;			
	}
</style>
<noscript>
	<style>
		.text-center{
			text-align:center;
		}
		.text-right{
			text-align:right;
		}
		table{
			width: 100%;
			border-collapse: collapse
		}
		tr,td,th{
			border:1px solid black;
		}
		.footer {
			margin-top: 50px;
			font-size: 18px;
			gap: 45%;
			display: flex;
			align-items: center;			
		}
	</style>
</noscript>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div class="col-md-12">
					<hr>
						<div class="row">
							<div class="col-md-12 mb-2">
							<button class="btn btn-sm btn-block btn-success col-md-2 ml-1 float-right" type="button" id="print"><i class="fa fa-print"></i> Print</button>
							</div>
						</div>
					<div id="report">
						<div class="on-print">
							 <h4 class="text-center">Sharmelle Apartment Management System</h4>
							 <p><center>Rental Balances Report</center></p>
							 <p><center>As of <b><?php echo date('F - Y'); ?></b></center></p>
						</div>
						<div class="row">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Tenant</th>
										<th>House</th>
										<th>House Type</th>
										<th>Monthly Rate</th>
										<th>Payable Months</th>
										<th>Payable Amount</th>
										<th>Paid</th>
										<th>Outstanding Balance</th>
										<th>Last Payment</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i = 1;
									// $tamount = 0;
									$tenants =$conn->query("SELECT 
																		t.*, 
																		CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS name, 
																		h.house_no, 
																		h.price, 
																		c.name AS category_name 
																	FROM 
																		tenants t 
																	INNER JOIN 
																		houses h 
																		ON h.id = t.house_id 
																	INNER JOIN 
																		categories c 
																		ON c.id = h.category_id 
																	WHERE 
																		t.status = 1 
																	ORDER BY 
																		h.house_no DESC;
																	");
									if($tenants->num_rows > 0):
									while($row=$tenants->fetch_assoc()):
										$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($row['date_in']." 23:59:59"));
										$months = floor(($months) / (30*60*60*24));
										$payable = $row['price'] * $months;
										$paid = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =".$row['id']);
										$last_payment = $conn->query("SELECT * FROM payments where tenant_id =".$row['id']." order by unix_timestamp(date_created) desc limit 1");
										$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
										$last_payment = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
										$outstanding = $paid - $payable;
									?>
									<tr>
										<td><?php echo $i++ ?></td>
										<td><?php echo ucwords($row['name']) ?></td>
										<td><?php echo $row['house_no'] ?></td>
										<td><?php echo $row['category_name'] ?></td>
										<td class="text-right"><?php echo number_format($row['price'],2) ?></td>
										<td class="text-right"><?php echo $months.' mo/s' ?></td>
										<td class="text-right"><?php echo number_format($payable,2) ?></td>
										<td class="text-right"><?php echo number_format($paid,2) ?></td>
										<td class="text-right"><?php echo number_format($outstanding,2) ?></td>
										<td><?php echo date('M d,Y',strtotime($last_payment)) ?></td>
									</tr>
								<?php endwhile; ?>
								<?php else: ?>
									<tr>
										<th colspan="9"><center>No Data.</center></th>
									</tr>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
						<div class="footer">
							<div class="signature text-lg font-semibold">Signature:</div>
							<div class="reported text-lg font-semibold">Reported By:</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#print').click(function(){
		var _style = $('noscript').clone()
		var _content = $('#report').clone()
		var nw = window.open("width=800,height=700");
		nw.document.write(_style.html())
		nw.document.write(_content.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
		nw.close()
		},500)
	})
	$('#filter-report').submit(function(e){
		e.preventDefault()
		location.href = 'index.php?page=payment_report&'+$(this).serialize()
	})
</script>