<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>Products</h1>
		</div>
		<div class="section-body">
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table class="table" id="ProductTable">
							<thead>
							<tr>
								<th>Id</th>
								<th>Product Code</th>
								<th>Product Name</th>
								<th>Description</th>
								<th>Action</th>
							</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php
$this->load->view('pageConfiguration/inventoryFormModal');
$this->load->view('_partials/footer');
?>

<script>
	$(document).ready(function () {
		getProductDetails();
	});


	function getProductDetails(){
		let formdata = new FormData();

		formdata.set('group_id',1);
		formdata.set('page_id',1);
		app.request("getProductDetails",formdata).then(res=>{
			$("#ProductTable").DataTable({
				destroy: true,
				order: [],
				"pagingType": "full_numbers",
				data:res.data,
				columns:[
					{data: 0},
					{data: 1},
					{data: 2},
					{data: 3},
					{
						data: 4,
						render: (d, t, r, m) => {
							return `<a href="${baseURL}pageConfiguration/${r[4]}" class="btn btn-icon btn-primary"><i
											class="fas fa-pen"></i></a>`
						}
					},
				],
				fnRowCallback:(nRow, aData, iDisplayIndex, iDisplayIndexFull) => {

				}
			});
		});
	}
</script>
