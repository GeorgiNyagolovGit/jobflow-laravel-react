# Deploying the backend to Render

The repository includes a root-level `render.yaml` Blueprint. It creates the
Laravel web service and a PostgreSQL database in Render's Frankfurt region.

## Deploy

1. From the repository root, generate the production key with
   `cd back-end && php artisan key:generate --show`, then keep it ready for
   step 3. Do not commit the key.
2. Push the whole repository to GitHub, GitLab, or Bitbucket.
3. In Render, choose **New > Blueprint** and select the repository.
4. Render will ask for these values:
   - `APP_KEY`: paste the key generated in step 1.
   - `APP_URL`: the Render backend URL, for example
     `https://job-app-api.onrender.com`.
   - `FRONTEND_URL`: the deployed frontend origin without a trailing slash,
     for example `https://job-app.vercel.app`. Multiple origins can be
     separated with commas.
5. Apply the Blueprint. Database migrations run automatically whenever the
   container starts.

The health check is available at `/up`.

The Blueprint uses Render's free plans for an initial/demo deployment. At the
time this file was created, free Render PostgreSQL databases expire after 30
days. Switch both `plan` values in `render.yaml` to paid plans before using the
application in production.

## Frontend

Set the frontend environment variable to the API base URL:

```env
VITE_API_URL=https://job-app-api.onrender.com/api
```

Because the frontend and backend are on different domains, production uses
secure `SameSite=None` session cookies. `FRONTEND_URL` must exactly match the
frontend origin.

## Uploaded files

Render's default filesystem is ephemeral. Avatars, company logos, and resumes
stored on the local disk can disappear after a restart or deploy. Before using
uploads in production, configure S3-compatible object storage or attach a
persistent disk on a paid Render service.

