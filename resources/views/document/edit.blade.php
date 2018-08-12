<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Create new document</title>

    <!-- CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- JS -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

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
                <a class="nav-link active" href="{{ url('documents') }}">
                  <span data-feather="home"></span>
                  My documents <span class="sr-only">(current)</span>
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
            <h1 class="h2">Edit a document</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <a class="btn btn-sm btn-outline-secondary" href='{{ route('index') }}'>Back to my documents</a> 
              </div>
            </div>
          </div>

          <script type="text/javascript">

                  $(document).ready(function() {
                      checkRemove()
                      //here first get the contents of the div with name class copy-fields and add it to after "after-add-more" div class.
                      $(".add-more").click(function(){
                          // var $newEntry =  $('div.after-add-more:last').after($('div.after-add-more:first').clone());
                          var $newEntry = $("div.after-add-more:last").clone(true);

                          $newEntry.find('input').each(function() {
                              var $this = $(this);
                              $this.attr('id', $this.attr('id').replace(/_(\d+)_/, function($0, $1) {
                                  return '_' + (+$1 + 1) + '_';
                              }));
                              $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function($0, $1) {
                                  return '[' + (+$1 + 1) + ']';
                              }));
                              $this.val('');
                          });
                          $newEntry.find('select').each(function() {
                              var $this = $(this);
                              $this.attr('id', $this.attr('id').replace(/_(\d+)_/, function($0, $1) {
                                  return '_' + (+$1 + 1) + '_';
                              }));
                              $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function($0, $1) {
                                  return '[' + (+$1 + 1) + ']';
                              }));
                          });
                          $newEntry.insertAfter('div.after-add-more:last:last');
                          checkRemove()
                      });
                      
                      //here it will remove the current value of the remove button which has been pressed
                      $("body").on("click",".remove",function(){
                          $('div.after-add-more:last').remove();
                          checkRemove()
                      });
                  });

                  function checkRemove() {
                      if ($('div.after-add-more').length == 1) {
                          $('button.remove:last').hide();
                      } else {
                          $('button.remove:last').show();
                      }
                  };
          </script>

          <div class="col-12">
            <form class="needs-validation" method="POST" action="{{ URL::to('/documents/'.$data->id) }}" novalidate>
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="put" />
              @foreach (json_decode($data->document) as $indexKey => $pairs)
                <div class="row after-add-more">
                  <div class="col-md-4 mb-3">
                    <label for="firstName">Key</label>
                    <input type="text" class="form-control" id="key" name="document[{{ $indexKey }}][key]" id="doc_{{ $indexKey }}_key" placeholder="" value="{{ $pairs->key }}" required>
                    <div class="invalid-feedback">
                      Valid first name is required.
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="country">Type</label>
                    <select class="custom-select d-block w-100" id="type" name="document[{{ $indexKey }}][type]" id="doc_{{ $indexKey }}_type" required>
                      <option value="string" {{ ("string" == $pairs->type ? "selected":"") }}>String</option>
                      <option value="number" {{ ("number" == $pairs->type ? "selected":"") }}>Number</option>
                      <option value="date" {{ ("date" == $pairs->type ? "selected":"") }}>Date</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="lastName">Value</label>
                    <input type="text" class="form-control" id="value" name="document[{{ $indexKey }}][value]" id="doc_{{ $indexKey }}_value" placeholder="" value="{{ $pairs->value }}" required>
                    <div class="invalid-feedback">
                      Valid last name is required.
                    </div>
                  </div>
                </div>
              @endforeach

              <div class="col-md-12 mb-3">
                <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
              </div>
              <hr class="mb-4">
              <button class="btn btn-primary btn-lg btn-block" type="submit">Save</button>
            </form>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
