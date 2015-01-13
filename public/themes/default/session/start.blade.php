 <!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="{{ theme('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="{{ theme('assets/css/vendor.css') }}" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{ theme('assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-black">

        <div class="form-box" id="login-box">
            <div class="header">{{ trans('session.signin') }}</div>
            <form action="{{ route('session.post.start') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="body bg-gray">
                    @if ($errors->any())
                    <div class="form-group">
                        <div class="callout callout-danger">
                            <p class="text-red">{{ $errors->first() }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <input type="text" name="{{ $login_id }}" value="{{ Input::old($login_id) }}" class="form-control" placeholder="{{ trans('session.' . $login_id) }}"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="{{ trans('session.password') }}"/>
                    </div>  
                    @if (setting('site.access.remember_me', true))        
                    <div class="form-group">
                        <input type="checkbox" name="remember_me"/> {{ trans('session.remember_me') }}
                    </div>
                    @endif
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">{{ trans('session.signmein') }}</button>  
                    @if (setting('site.access.allow_pass_reset', true))
                    <p><a href="{{ route('password.get.email') }}">{{ trans('session.iforgot') }}</a></p>
                    @endif
                    @if (setting('site.access.reg_method', 'public') == 'public')
                    <a href="{{ route('session.get.register') }}" class="text-center">{{ trans('session.register') }}</a>
                    @endif
                </div>
            </form>
        </div>


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <!-- // <script src="{{ theme('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>         -->
        <script src="{{ theme('assets/js/vendor.js') }}" type="text/javascript"></script>        
        <script src="{{ theme('assets/js/app.js') }}" type="text/javascript"></script>        

    </body>
</html>