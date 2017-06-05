{{--/**--}}
{{--* Created by: Josh Gerlach.--}}
{{--* Authors: Josh Gerlach--}}
{{--*/--}}

@include('layouts.header')
@include('layouts.navbar')

@section('title','Pineapple')

    <style>

        thead{
            background-color: black;
        }

    </style>
<div class="content-box bg" style="padding-top:3%; ">
    <h1 class=" heading ">Find Users</h1>
</div>

    {{--User Search with autocomplete--}}
    <div class="form-group content-box " style="padding-bottom: 0; padding-top: 3%;">
        <strong >Start typing your friends name into the search bar below to find them</strong>
        <input type="text" class="form-control" placeholder="Search" id="usersAutocomplete">
        <div id="usersList" style="position: absolute; z-index: 300; background-color: #FFFFFF; width: 100%">
        </div>
    </div>

    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

<script>


    $(document).ready(function(){ // this will be called when the DOM is ready

        //When there is a key up inside the searchbar, search through the stocks list and return a max of 10 results to auto complete
        $('#usersAutocomplete').keyup(function() {

            var users_query = $('#usersAutocomplete').val(); //Get query

            //Get all stocks names and ASX codes from API
            $.get("{{url('api/users')}}/" + users_query)// 'http://localhost:8000/api/all-stocks')
                .done(function (data) {
                    //Empty the autocomplete list ready for new search results
                    $('#usersList').empty();

                    //Test if the returned data is null or contains no results, and that the number of characters
                    //In the search bar are greter than 0
                    if ((data == null || data.length == 0) && $('#usersAutocomplete').val().length > 0)
                    {
                        //Say that there are no search results
                        $('#usersList').append('' +
                            '<p style="margin: 0; padding: 10px;">' + "no results found" + '<br /></p>'
                        );
                        return;
                    }


                    //Go through each found in the API usersList
                    $.each(data, function(i, item) {
                        //Add User row with link to profile page to the autocomplete div
                        $('#usersList').append('' +
                            '<a href ="/profile/' + item["id"] +'" ><p class="suggestion col-xs-9" style="margin: 0; padding: 10px;">' + item["name"] + '<br /></p></a>'
                        );
                    });
                })
                //If the call fails, make sure that the user list is empty
                .fail(function (error){
                    $('#usersList').empty();
                    //Say that there are no search results
                    $('#usersList').append('' +
                        '<p style="margin: 0; padding: 10px;">' + "no results found" + '<br /></p>'
                    );
                })
            ;

        });

    });

</script>

@include('layouts.footer')