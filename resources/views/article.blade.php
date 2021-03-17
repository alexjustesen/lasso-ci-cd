<x-app-layout>
    <div class="max-w-4xl mx-auto my-4 space-y-4 sm:my-6 lg:my-8 sm:space-y-6 lg:space-y-8">
        <x-card>
            <x-navigation />

            <div class="pt-4 sm:pt-6 lg:pt-8">
                <article class="prose max-w-none lg:prose-xl">
                    <h1 id="home">Laravel CI/CD Pipeline with Lasso and GitHub Actions</h1>

                    <div class="flex items-center space-x-4">
                        <a href="https://ko-fi.com/S6S12TS60" target="_blank" rel="noopener">
                            <img src="https://cdn.ko-fi.com/cdn/kofi3.png?v=2" class="h-8 border-none" style="margin: 0;" alt="Buy Me a Coffee at ko-fi.com">
                        </a>
                    </div>

                    <p>
                        This guide will walk you though how I use GitHub Actions to create a CI/CD pipeline for <a href="https://laravel.com" target="_blank" rel="noopener">Laravel</a> applications. We'll use
                        <a href="https://getlasso.dev" target="_blank" rel="noopener">Lasso</a> from <a href="https://github.com/Sammyjo20" target="_blank" rel="noopener">Sam Carré</a> to compile Webpack
                        assets and upload them to a S3 storage provider and then trigger a deployment using the workflow.
                    </p>

                    <blockquote>
                        <p><strong>Warning</strong>: This article is very much a work-in-progress and in need of polishing my brain dump.</p>
                    </blockquote>

                    <h2 id="getting-started">Getting Started</h2>

                    <h3>What is CI/CD?</h3>
                    <p>
                        "CI/CD" is Continuous (Integeration or Iteration) / Continuous (Development, Delivery or Deployment) is a process of developing, testing and deploying your code.
                        You'll also likely see this referred to as an "Agile Development Cycle".
                    </p>

                    <p>
                        I recently answered a <a href="https://www.reddit.com/r/laravel/comments/j9fsa4/how_to_manage_and_maintain_laravel_application/g8kzlv1?utm_source=share&utm_medium=web2x&context=3" target="_blank" rel="noopener">Reddit</a> thread
                        which sparked this whole thing. If you're still unsure what CI/CD is I suggest doing some <a href="https://googlethatforyou.com?q=what%20is%20ci%2Fcid" target="_blank" rel="noopener">Googling</a>.
                    </p>

                    <h3>Assumptions</h3>
                    <p>
                        Let's all get on the same page so we don't make a 🍑 out of you and me.
                    </p>

                    <ul>
                        <li>You're using GitHub as your source code management (SCM) platform and/or want to use GitHub Actions.</li>
                        <li>
                            You're using <a href="https://laravel.com" target="_blank" rel="noopener">Laravel</a> and that you're familare with Laravel's file system and in specific using
                            the <a href="https://laravel.com/docs/8.x/filesystem#composer-packages" target="_blank" rel="noopener">S3 driver</a>.
                        </li>
                        <li>
                            You compile your assets using <a href="https://laravel.com/docs/8.x/mix#introduction" target="_blank" rel="noopener">Laravel Mix</a> and they get published into the default <code>/public</code> directory.
                        </li>
                    </ul>

                    <h3 id="branching">Branching</h3>
                    <p>
                        For this example we're going to use a pretty simple <span class="line-through">master</span> main, develop and feature branching model. Develop will be our default branch where all code is based from and main will be our production code.
                    </p>

                    <img src="https://wac-cdn.atlassian.com/dam/jcr:a9cea7b7-23c3-41a7-a4e0-affa053d9ea7/04%20(1).svg?cdnVersion=1315" alt="">

                    <blockquote>
                        <p>Source: <a href="https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow" target="_blank" rel="noopener">https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow</a></p>
                    </blockquote>

                    <h3>Process</h3>
                    <p>
                        We're going to go into some detail but the overall summary of the CI/CD pipeline looks like this.
                    </p>

                    <ol>
                        <li>Create a new branch off <code>develop</code> to do your work in.</li>
                        <li>Create a pull request (PR) to bring those changes into the develop branch.</li>
                        <li>On the creation of the PR into our <code>develop</code> branch run our builds and tests.</li>
                        <li>When merged into <code>develop</code> run a workflow to compile and upload the assets to an S3 bucket using Lasso and deploy the code.</li>
                        <li>Once you have enough code in <code>develop</code> for a release, open a PR into the <code>main</code> branch and we'll repeat steps 3 and 4 for production.</li>
                    </ol>

                    <h2 id="configuring">Configuring</h2>

                    <h3>Installing dependencies</h3>
                    <p>
                        First we need to require a couple of packages.
                    </p>

                    <ul>
                        <li>Require Amazon S3 <code>composer require league/flysystem-aws-s3-v3 ~1.0</code>.</li>
                        <li>Require Lasso <code>composer require sammyjo20/lasso</code>.</li>
                    </ul>

                    <h3>Configuring Laravel</h3>
                    <p>
                        Next we need to update Laravel's <code>.env</code> file with additional S3 driver options and the Lasso environment.
                        (<a href="https://github.com/alexjustesen/lasso-ci-cd/blob/develop/.env.example" target="_blank" rel="noopener">Example</a>)
                    </p>

                    <ul>
                        <li>Add <code>AWS_ENDPOINT=</code> after <code>AWS_BUCKET</code>.</li>
                        <li>Add <code>LASSO_ENV=</code> after <code>APP_</code> variables.</li>
                    </ul>

                    <h3>Configuring Lasso</h3>
                    <p>
                        Next let's get Lasso configured, for a full explaination of the settings go to the Lasso <a href="https://github.com/Sammyjo20/Lasso#configuration" target="_blank" rel="noopener">readme</a>.
                    </p>

                    <ol>
                        <li>Publish Lasso's config file <code>php artisan vendor:publish --tag=lasso-config</code>.</li>
                        <li>In <code>config/lasso.php</code> change <code>'disk' => 'assets'</code> to <code>'disk' => 's3'</code> to use the S3 drivers.</li>
                        <li>
                            Optional, change <code>upload_to</code> if you plan on using the same bucket for multiple sites.
                            You'll notice in this repository I've set it to <code>lasso_ci_cd/lasso</code>.
                        </li>
                    </ol>

                    <h3>Configure .gitignore</h3>
                    <p>
                        Since the whole point of this is to automate our deployments and no longer commit our assets to GitHub we need to update our <code>.gitignore</code> file as well.
                    </p>

                    <ul>
                        <li>Add Lasso's temp directory <code>.lasso</code>.</li>
                        <li>Add our public asset directories and any additional directories and files.
                            <ul>
                                <li><code>/public/css/*</code></li>
                                <li><code>/public/img/*</code></li>
                                <li><code>/public/js/*</code></li>
                                <li><code>/public/favicon.ico</code></li>
                                <li><code>/public/mix-manifest.json</code></li>
                            </ul>
                        </li>
                    </ul>

                    <h2 id="github-actions">GitHub Actions</h2>
                    <p>
                        To get us started we're going to create and run two workflows, one to do our testing (CI) and one to do our deploying (CD).
                    </p>

                    <h3>Continuous Improvement Workflow (Test)</h3>
                    <p>
                        The goal of this workflow is to run our build and tests when a PR is opened and when any changes are made to code where a PR is already open.
                    </p>

                    <h4>Workflow Additions</h4>
                    <p>
                        We're only going to add one section here to make sure our NPM dependencies install and package correctly.
                        After the <code>Copy .env</code> step add the <code>Install npm dependencies and package</code> step to your workflow.
                    </p>

                    <blockquote>
                        <p>
                            This workflow can be found here: <a href="https://github.com/alexjustesen/lasso-ci-cd/blob/develop/.github/workflows/test.yml" target="_blank" rel="noopener">test.yml</a>.
                        </p>
                    </blockquote>

                    <h3>Continuous Deployment Workflow (Deploy)</h3>
                    <p>
                        The goal of this workflow is to run when a PR is merged into develop or mail, run our tests, compile and upload assets with Lasso and finally deploy the code.
                    </p>

                    <blockquote>
                        <p>
                            This workflow can be found here: <a href="https://github.com/alexjustesen/lasso-ci-cd/blob/develop/.github/workflows/deploy.yml" target="_blank" rel="noopener">deploy.yml</a>.
                        </p>
                    </blockquote>

                    <h4>Using Secrets</h4>
                    <blockquote>
                        <p>
                            Important: Never hard code passwords or tokens into your code, use secrets.
                        </p>
                    </blockquote>

                    <p>
                        Before we can use secrets in our workflow we need to create them in the repository. Go to Settings -> Secrets in your repository and add the following variables.
                    </p>

                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Value</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>AWS_ACCESS_KEY_ID</td>
                                <td>Also known as the public key to your account or API user.</td>
                            </tr>

                            <tr>
                                <td>AWS_SECRET_ACCESS_KEY</td>
                                <td>Also known as the secret key to your account or API user.</td>
                            </tr>

                            <tr>
                                <td>AWS_DEFAULT_REGION</td>
                                <td>The default region where you bucket resides.</td>
                            </tr>

                            <tr>
                                <td>AWS_BUCKET</td>
                                <td>The buckets name.</td>
                            </tr>

                            <tr>
                                <td>AWS_ENDPOINT</td>
                                <td>The endpoint should be a fully qualified domain name including the protocol (https://).</td>
                            </tr>
                        </tbody>
                    </table>

                    <blockquote>
                        <p>Note: You can use any s3 compliant API service, I use Backblaze's B2 service because it's cheaper than AWS.</p>
                    </blockquote>

                    <p>
                        In addition to the changes we made to test our NPM dependencies we'll add additional steps to complete the process.
                    </p>

                    <p>
                        We're going to add a new step to compile and upload our assets to our S3 bucket and we'll call
                        it <code>Publish assets with Lasso</code>.
                    </p>

                    <h2 id="deployment">Deployment</h2>

                    <p>
                        Lastly you need to add <code>php artisan lasso:pull</code> to your deployment script, I suggest doing this prior to clearing or filling any caches.
                    </p>

                    <p>
                        Almost forgot, don't forget to update your S3 variables in your <code>.env</code> file.
                    </p>

                    <p>
                        Well, that's pretty much it. At this point you should be up and running, should you have an issues getting this setup
                        <a href="https://twitter.com/alexjustesen" target="_blank" rel="noopener">Twitter</a> is the best way to reach me.
                    </p>

                    <h3>Bonus</h3>
                    <p>
                        I use <a href="https://ploi.io/register?referrer=Q6l9H3OiyYW58yEcwjy8" rel="nofollow" target="_blank">Ploi.io</a> <em>(referral link)</em> and
                        <a href="Https://forge.laravel.com" rel="nofollow" target="_blank">Forge</a> to host my Laravel applications.
                    </p>

                    <p>
                        The steps called <code>Trigger develop deployment</code> and
                        <code>Trigger production deployment</code> in <a href="https://github.com/alexjustesen/lasso-ci-cd/blob/develop/.github/workflows/deploy.yml" target="_blank" rel="noopener">deploy.yml</a>
                        deploy to different environments based on branch name.
                    </p>

                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Branch/Environment</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>DEVELOP_WEBHOOK</td>
                                <td>develop/develop</td>
                            </tr>

                            <tr>
                                <td>PROD_WEBHOOK</td>
                                <td>main/production</td>
                            </tr>
                        </tbody>
                    </table>

                    <p>
                        This pattern allows me to deploy code from my <code>develop</code> branch to my <code>preview</code> environment and code from my <code>main</code> branch to my <code>production</code> environment.
                    </p>
                </article>
            </div>
        </x-card>

        <x-card>
            <div class="prose-sm prose max-w-none lg:prose-xl">
                <h2 id="disclaimer">Disclaimer</h2>
                <p>
                    CI/CD pipelines have many forms, each is specific to the needs of the project and <strong>should evolve over time</strong>.
                    This is just one way of doing it, find the process that works for you.
                </p>
            </div>
        </x-card>

        <x-card>
            <div class="prose-sm prose max-w-none lg:prose-xl">
                <h2 id="about-me">About me</h2>
                <p>
                    I'm Alex, I've done some form of web development for the better part of 15 years. I currently work for <a href="https://massmutual.com" target="_blank" rel="noopener">MassMutual</a> doing operational
                    data architecture and web development for our insurance business lines.
                </p>

                <p>
                    I also do a bit of feelance work to pay for 🍺 and 🚗.
                </p>

                <p>You can get ahold of me through the methods below.</p>

                <ul>
                    <li><a href="https://github.com/alexjustesen" target="_blank" rel="noopener">GitHub</a></li>
                    <li><a href="https://twitter.com/alexjustesen" target="_blank" rel="noopener">Twitter</a></li>
                    <li><a href="https://www.linkedin.com/in/alexander-justesen/" target="_blank" rel="noopener">LinkedIn</a></li>
                </ul>
            </div>
        </x-card>

        <x-footer />
    </div>
</x-app-layout>
