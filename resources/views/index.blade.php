<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>My documents</title>

    <!-- CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- JS -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript">
      function deleteItem(id) {
          if (confirm("Are you sure?")) {

              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });

              $.ajax({
                url: 'documents/' + id,
                type: 'DELETE',
                data: "name=John&location=Boston",
                success: function(data) {
                  window.location.href=window.location.href;
                }
              });
          }
          return false;
      }

      function exportDoc(id, type = 'csv') {
          if (confirm("Are you sure?")) {

              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });

              $.ajax({
                url: 'documents/' + id,
                type: 'GET',
                data: "format=csv",
                success: function(data) {
                  alert('asd');
                }
              });
          }
          return false;
      }
    </script>

    <!-- Styles -->
    <style>
      html, body {
        background-color: #fff;
        color: #636b6f;
        font-family: 'Raleway', sans-serif;
        font-weight: 100;
        height: 100vh;
        margin: 0;
      }

      .feather {
			  width: 16px;
			  height: 16px;
			  vertical-align: text-bottom;
			}

			/*
			 * Sidebar
			 */

			.sidebar {
			  position: fixed;
			  top: 0;
			  bottom: 0;
			  left: 0;
			  z-index: 100; /* Behind the navbar */
			  padding: 48px 0 0; /* Height of navbar */
			  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
			}

			.sidebar-sticky {
			  position: relative;
			  top: 0;
			  height: calc(100vh - 48px);
			  padding-top: .5rem;
			  overflow-x: hidden;
			  overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
			}

			@supports ((position: -webkit-sticky) or (position: sticky)) {
			  .sidebar-sticky {
			    position: -webkit-sticky;
			    position: sticky;
			  }
			}

			.sidebar .nav-link {
			  font-weight: 500;
			  color: #333;
			}

			.sidebar .nav-link .feather {
			  margin-right: 4px;
			  color: #999;
			}

			.sidebar .nav-link.active {
			  color: #007bff;
			}

			.sidebar .nav-link:hover .feather,
			.sidebar .nav-link.active .feather {
			  color: inherit;
			}

			.sidebar-heading {
			  font-size: .75rem;
			  text-transform: uppercase;
			}

			/*
			 * Content
			 */

			[role="main"] {
			  padding-top: 48px; /* Space for fixed navbar */
			}

			/*
			 * Navbar
			 */

			.navbar-brand {
			  padding-top: .75rem;
			  padding-bottom: .75rem;
			  font-size: 1rem;
			  background-color: rgba(0, 0, 0, .25);
			  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
			}

			.navbar .form-control {
			  padding: .75rem 1rem;
			  border-width: 0;
			  border-radius: 0;
			}

			.form-control-dark {
			  color: #fff;
			  background-color: rgba(255, 255, 255, .1);
			  border-color: rgba(255, 255, 255, .1);
			}

			.form-control-dark:focus {
			  border-color: transparent;
			  box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
			}
    </style>
  </head>
  <body>
  	<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Formstack</a>
    </nav>
    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <span data-feather="home"></span>
                  My documents <span class="sr-only">(current)</span>
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
            <h1 class="h2">Documents</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <a class="btn btn-sm btn-outline-secondary" href="{{ url('documents/create') }}">Create new document</a> 
                <!-- <button class="btn btn-sm btn-outline-secondary">Export</button> -->
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Created at</th>
                  <th>Updatet at</th>
                  <th>Exported at</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($documents as $document)
                  <tr>
                    <td>{{ $document->title }}</td>
                    <td>{{ $document->created_at }}</td>
                    <td>{{ $document->updated_at }}</td>
                    <td>{{ $document->exported_at }}</td>
                    <td>
                      <a class="btn btn-sm btn-outline-secondary" href="{{ url('documents/'.$document->id.'/edit') }}"'>Edit</a> 
                      <a class="btn btn-sm btn-outline-secondary" onclick='deleteItem({{ $document->id }})'>Delete</a> 
                      <a class="btn btn-sm btn-outline-secondary" href="{{ url('documents/'.$document->id.'?format=csv') }}"'>Export to CSV</a> 
                      <a class="btn btn-sm btn-outline-secondary" href="{{ url('documents/'.$document->id.'?format=cloud') }}"'>Export to cloud</a>  
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
