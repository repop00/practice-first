<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Social Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .post-card {
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .comment-section {
            background-color: #f8f9fa;
            border-radius: 0 0 10px 10px;
            padding: 15px;
        }
        .comment-item {
            border-left: 3px solid #dee2e6;
            padding-left: 10px;
            margin-bottom: 10px;
        }
        .timestamp {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="#">SocialApp</a>

            <div class="ml-auto d-flex align-items-center">
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center" href="#" role="button" id="profileDropdown"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random"
                             class="profile-img mr-2">
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i>Profile</a>
                        <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Settings</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Create Post Section -->
        <div class="card post-card mb-4">
            <div class="card-body">
                <h5 class="card-title">Create a Post</h5>
                <form action="{{ route('storepost') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Your name"
                               value="{{ Auth::user()->name }}" readonly>
                         <input type="hidden" class="form-control" name="email" value="{{ Auth::user()->email }}" readonly>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="postcontent" rows="3"
                                  placeholder="What's on your mind?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
        </div>

        <!-- Posts Section -->
        <h4 class="mb-4">Recent Posts</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @foreach ($posts as $post)
        <div class="card post-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ $post->username }}&background=random"
                         class="profile-img mr-3">
                    <div>
                        <h6 class="mb-0 font-weight-bold">{{ $post->username }}</h6>
                        {{-- <small class="timestamp">{{ $post->created_at->diffForHumans() }}</small> --}}
                        <small class="timestamp">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</small>
                    </div>
                </div>
                <p class="card-text">{{ $post->postcontent }}</p>
            </div>

            <!-- Comments Section -->
            <div class="comment-section">
                <h6 class="font-weight-bold">Comments</h6>

                @if ($post->comments && $post->comments->count() > 0)
                    @foreach ($post->comments as $comment)
                    <div class="comment-item mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <img src="https://ui-avatars.com/api/?name={{ $comment->user->name ?? 'Anonymous' }}&background=random"
                                 class="profile-img mr-2" style="width:30px;height:30px;">
                            <strong>{{ $comment->user->name ?? 'Anonymous' }}</strong>
                        </div>
                        <p class="mb-1">{{ $comment->commentcontent }}</p>
                        <small class="timestamp">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No comments yet. Be the first to comment!</p>
                @endif

                <!-- Add Comment Form -->
                <form action="{{ route('storecomment', $post->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="input-group">
                        <input type="text" class="form-control" name="commentcontent"
                               placeholder="Write a comment...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-primary">Post</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"></script>
</body>
</html>
