function addTask() {
    const taskInput = document.getElementById("task-input");
    const taskText = taskInput.value.trim();

    if (taskText === "") {
        alert("Please enter a task.");
        return;
    }

    // Create a new task item
    const taskItem = document.createElement("div");
    taskItem.classList.add("task-item");

    const taskTextElem = document.createElement("span");
    taskTextElem.classList.add("task-text");
    taskTextElem.innerText = taskText;

    // Task actions (Finish and Delete buttons)
    const taskActions = document.createElement("div");
    taskActions.classList.add("task-actions");

    // Finish button
    const finishBtn = document.createElement("button");
    finishBtn.classList.add("finish-btn");
    finishBtn.innerText = "Finish";
    finishBtn.onclick = () => {
        taskTextElem.style.textDecoration = "line-through";
        finishBtn.disabled = true; // Disable the button after finishing
    };

    // Delete button
    const deleteBtn = document.createElement("button");
    deleteBtn.classList.add("delete-btn");
    deleteBtn.innerText = "Delete";
    deleteBtn.onclick = () => {
        taskItem.remove();
    };

    taskActions.appendChild(finishBtn);
    taskActions.appendChild(deleteBtn);

    taskItem.appendChild(taskTextElem);
    taskItem.appendChild(taskActions);

    // Append task to task list
    const taskList = document.getElementById("task-list");
    taskList.appendChild(taskItem);

    // Clear input
    taskInput.value = "";
}
