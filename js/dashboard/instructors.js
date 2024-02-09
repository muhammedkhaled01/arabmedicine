$(document).ready(function(){
    var instructors=$('#instructors').DataTable({
        dom:"lBfrtip",
        processeing:true,
        serverSide:true,
        destroy:true,
        ajax:{
            url:site_url+"/admin/instructors",
            type:"GET",
        },
        columns:[
            {
                data:"id",
                name:"id"
            },
            {
                data:"name",
                name:"name",
                render:function(d,t,r,m){
                    return r.firstname+" "+r.lastname
                }
            },
            {
                data:"email",
                name:"email"
            },
            {
                data:"role",
                name:"role"
            },
            {
                data:"action",
                name:"action",
                render:function(d,t,r,m){

                    return  `
                    <script>
                    $('.select2').select2({
                        placeholder: 'Choose one',
                        searchInputPlaceholder: 'Search'
                    });
                    </script>
                    
                    <div class="d-flex align-items-center">
                    <select class="select2 select-role" id="select-role-${r.id}">
                        <option value="" ${r.role=='instructor'?'selected':''}>No Role</option>
                        <option value="instructor" ${r.role==null?'selected':''}>Instructor</option>
                    </select>
                    <button type="button" data-id="${r.id}" class="btn btn-primary save-role ml-2">Change</button>
                    </div>
                    `
                }
            },
            
        ],
        columnDefs:[{
            targets:[0,1,2,3,4],
            searchable:true
        }],
        ordering:false
    })
    $(document).on('click','.save-role',function(){
        var id=$(this).data('id');
        var role=$('#select-role-'+id).val();
        var data={
            '_token':csrf_token,
            'id':id,
            'role':role
        }
        $.post(update_user_role,data,function(response){
            if(response.success){
                instructors.ajax.reload();
            }
        })
        
    })


})