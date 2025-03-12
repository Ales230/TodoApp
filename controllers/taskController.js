const Task = require("../models/task");

// ...existing code...

exports.editTask = async (req, res) => {
  try {
    const { taskId, taskName, taskDescription } = req.body;
    await Task.findByIdAndUpdate(taskId, {
      name: taskName,
      description: taskDescription,
    });
    res.redirect("/tasks");
  } catch (error) {
    res.status(500).send(error);
  }
};
