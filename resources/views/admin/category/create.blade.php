@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="#" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
        <form action ="" method ="post" id="categoryForm" name="categoryForm">
            @csrf    
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">	
                                <p></p>
                                </div>
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="hidden" name="image_id" id="image_id" value="">	
                                <label for="image">Image</label>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">    
                                        <br>Drop files here or click to upload.<br><br>                                            
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name = "status" id ="status" class="form-control">
                                    <option value="1"> Active</option>
                                    <option value="0"> Block</option>
                                </select>
                            </div>
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="showHome">Show On Home</label>
                                <select name = "showHome" id ="showHome" class="form-control">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>									
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type ="submit" class="btn btn-primary">Create</button>
                <a href="#" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
<script>
    Dropzone.autoDiscover = false;
    const dropzone = new Dropzone("#image", {
        url: "{{ route('temp-images.create') }}",
        maxFiles: 1,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            if (response.status) {
                $("#image_id").val(response.image_id);
            }
        },
        error: function(file, response) {
            console.log(response);
        }
    });

    $('#categoryForm').submit(function(event) {
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{ route("categories.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);
                if (response.status) {
                    window.location.href = "{{ route('categories.index') }}";
                } else {
                    // Handle validation errors
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
                console.log(jqXHR.responseText); // Add this to get detailed error message
            }
        });

    });

    $("#name").change(function() {
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: { title: element.val() },
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);
                if (response.status) {
                    $("#slug").val(response.slug);
                }
            }
        });
    });
</script>
@endsection