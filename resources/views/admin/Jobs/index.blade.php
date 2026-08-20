<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Jobs</title>
</head>

<body>

    <h1>Manage Jobs</h1>

    <p>
        Welcome,
        {{ Auth::user()->name }}
    </p>

    @if(session('success'))

        <div>
            {{ session('success') }}
        </div>

    @endif


    <div>

        <a href="{{ route('admin.jobs.create') }}">
            + Create New Job
        </a>

        &nbsp;

        <a href="{{ route('admin.dashboard') }}">
            Admin Dashboard
        </a>

    </div>

    <hr>


    @forelse($jobs as $job)

        <div>

            <h2>
                {{ $job->title }}
            </h2>

            <p>
                <strong>Company:</strong>
                {{ $job->company }}
            </p>

            @if($job->location)

                <p>
                    <strong>Location:</strong>
                    {{ $job->location }}
                </p>

            @endif

            @if($job->job_type)

                <p>
                    <strong>Job Type:</strong>
                    {{ $job->job_type }}
                </p>

            @endif

            @if($job->experience)

                <p>
                    <strong>Experience:</strong>
                    {{ $job->experience }}
                </p>

            @endif

            <a href="{{ route('jobs.show', $job) }}">
                View
            </a>

        </div>

        <hr>

    @empty

        <p>
            No jobs found.
        </p>

    @endforelse


    {{ $jobs->links() }}

</body>

</html>