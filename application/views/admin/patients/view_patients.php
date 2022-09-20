<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<!-- Main Content start -->
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>Patient Details</h1>
		</div>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-primary">
						<div class="card-header">
							<h4>Patient Details</h4>
							
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table style="width: 100%"  class="table dataTable"  id="patientTable">
									<thead>
									<tr>
										<td  data-priority="1">Aadhar Number</td>
										<td  data-priority="2">Name</td>
										
										<td >Contact</td>
										<td >Birth Date</td>
										<td >Location</td>
										<td >Blood Group</td>
										<td >Action</td>
									</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</section>
</div>
<!-- Main Content end -->
<?php $this->load->view('_partials/footer'); ?>

