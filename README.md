# docker.simpleDo
AI created ToDo list, just created with an unstructured imperative

## What it is
This is the result of my worst-case test-case with aider, the openai programming helper for almost any language.
To get this App, I did almost anything you shouldent.
* My initial request was: write a todo list in php with sqlite as backend.
* predefined no expection of the desired result
* defined no expection of all the results I want to get back
* requested all the functionality, such as css, subtasks, dates, options, layout, ... step by step
* So I got an unstructured app, which is working
* Now I try if it can pack it into a docker container, which I can use instead of the boring hello world projects ...

## How to Build and Run the Docker Container
1. Build the Docker image by running the following command in the terminal:
   ```
   docker build -t simpledo .
   ```
2. Run the Docker container by running the following command in the terminal:
   ```
   docker run -p 8080:80 simpledo
   ```
After running these commands, you can access the application by opening a web browser and navigating to `http://localhost:8080`.

