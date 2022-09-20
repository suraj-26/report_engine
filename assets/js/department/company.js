$(document).ready(function(){
    // alert("hii");
    var dataTable =$('#company_table').DataTable({
        // "processing":true,
        // "severSide":true,
        // "order":[],
        "ajax":'http://localhost/new_covid/CompaniesController/fetch_user'
            // type:"POST"
        //     // dataType:"JSON"
        //     // success:function(data){console.log(data);}
        

        // "columnDefs":[
        // {
        //     "targets":[1],
        //     "orderable":false

        // }]

    });



});
function Login_display_alert()
{
    setTimeout(function(){
        $(".err_msg").html('');
        $("input").css({'border':' 1px solid #ccc'});
         $(".bor_col").css({'border':' 1px solid #ccc'});
       

    },4000);
}
function add_company()
{
	
  
    var formdata=$("#company_form").serialize();
    // alert(formdata);
    $.ajax({
            type:'POST',
            url:"http://localhost/new_covid/"+"CompaniesController/add_company",
            data:formdata,
            success:function(resp)
            {
                // alert(resp);
                if (resp==0) {
                    alert('');
                }
                else
                {
                   
                    setTimeout(function()
                    {
                        window.location.href=baseURL+"view_companies";
                    },3000);
                }
            }

        });
}
function delete_company(id)
{
	   $.ajax({
            type:'POST',
            url:"http://localhost/new_covid/"+"CompaniesController/delete_company/"+id,
            data:'',
            success:function(resp)
            {
                // alert(resp);
                if (resp==0) {
                   
                }
                else
                {
                   
                    setTimeout(function()
                    {
                        window.location.href=baseURL+"view_companies";
                    },3000);
                }
            }

        });
}
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("mytable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}


function update_form(id)
{
  // alert(id);

  $.ajax({
            type:'POST',
            url:"http://localhost/new_covid/"+'CompaniesController/select_company/'+id,
            data:'',
            dataType:'JSON',
            success:function(resp)
            {
                // console.log(resp);
                // var modal = document.getElementById("myModal");
                // modal.style.display = "block";
                document.getElementById("c_id").value=resp[0]['id'];
                document.getElementById("name").value=resp[0]['name'];
               
                

                // if (resp==0) {
                //     alert('incorrect user name and password');
                // }
                // else
                // {
                //     alert('successfully log in');
                //     setTimeout(function()
                //     {
                //         window.location.href=base_url+"Home";
                //     },3000);
                // }
            }

        });
}
