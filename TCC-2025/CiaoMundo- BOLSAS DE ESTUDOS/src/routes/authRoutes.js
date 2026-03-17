const express = require("express");
const router = express.Router();
const authController = require("../controllers/authController");

//Cadastro e login

router.post("/register", authController.registrar);
router.post("/login", authController.login);
router.post("/logout", authController.logout);

module.exports = router;
