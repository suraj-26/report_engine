$(document).ready(function(){
	getTemplateList();
});
function getTemplateList()
{
	 app.request("getAllTemplateList",null).then(res=>{
        var count = 1;
        $("#templateTable").DataTable({
            destroy: true,
            order: [],
            "pagingType": "full_numbers",
            data:res.data,
            columns:[
            	{data: 0},
                {data: 1},
                {data: 2},
               	{data: 3,
                    render: (d, t, r, m) => {
                    	if(r[3]==1)
                    	{
                    		 return `<a class="btn btn-icon btn-link">Active</a>`;
                    	}
                    	else
                    	{
                    		 return `<a class="btn btn-icon btn-link">Inactive</a>`;
                    	}
                    }
                },
                {
                    data: 4,
                    render: (d, t, r, m) => {
                        return `<a href="${baseURL}pageConfiguration/${r[4]}" class="btn btn-icon btn-primary"><i
											class="fas fa-pen"></i></a>
											<a href="${baseURL}viewForm/${r[4]}" class="btn btn-icon btn-primary"><i
											class="fas fa-eye"></i></a>`
                    }
                },
            ],
            fnRowCallback:(nRow, aData, iDisplayIndex, iDisplayIndexFull) => {
            	if(aData[3]==1)
            	{
            		 $('td:eq(3)', nRow).html(`<a class="btn btn-icon btn-link">Active</a>`);
            	}
            	else
            	{
            		  $('td:eq(3)', nRow).html(`<a class="btn btn-icon btn-link">Inactive</a>`);
            	}
                $('td:eq(4)', nRow).html(`<a href="${baseURL}pageConfiguration/${aData[4]}" class="btn btn-icon btn-primary"><i
											class="fas fa-pen"></i></a>
											<a href="${baseURL}viewForm/${aData[4]}" class="btn btn-icon btn-primary"><i
											class="fas fa-eye"></i></a>`);
            }
        });
    })
}