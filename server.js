const express = require("express");
const csrf = require("csurf");
const cookieParser = require("cookie-parser");

const app = express();
const csrfProtection = csrf({ cookie: true });

app.use(cookieParser());
app.use(csrfProtection);

app.get("/form", (req, res) => {
  res.render("form", { csrfToken: req.csrfToken() });
});

app.post("/submit", csrfProtection, (req, res) => {
  res.send("Form submitted successfully");
});

app.listen(3000, () => {
  console.log("Server is running on port 3000");
});
