import React, { useState } from "react";

function App() {
  const [filter, setFilter] = useState("all");

  const handleFilterChange = (e) => {
    setFilter(e.target.value);
  };

  const filteredTasks = tasks.filter((task) => {
    if (filter === "completed") {
      return task.completed;
    } else if (filter === "incomplete") {
      return !task.completed;
    }
    return true;
  });

  return (
    <div className="App">
      {}
      <div>
        <label>
          Filter:
          <select value={filter} onChange={handleFilterChange}>
            <option value="all">All</option>
            <option value="completed">Completed</option>
            <option value="incomplete">Incomplete</option>
          </select>
        </label>
      </div>
      <ul>
        {filteredTasks.map((task) => (
          <li key={task.id}>{}</li>
        ))}
      </ul>
      {}
    </div>
  );
}

export default App;
