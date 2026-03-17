// // Express route handler
// app.post('/login', async (req, res) => {
//   const { email, senha } = req.body;
//   const usuario = await db.getUserByEmail(email);
//   if (!usuario) return res.status(401).send('Usuário não encontrado');
//   const match = await bcrypt.compare(senha, usuario.senhaCriptografada);
//   if (match) {
//     // Login aprovado
//   } else {
//     // Senha incorreta
//   }
// });
