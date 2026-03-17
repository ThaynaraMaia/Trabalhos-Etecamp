**Trabalho de Conclusão de Curso — Plataforma CiaoMundo**

**Bem-vindo(a) ao TCC CiaoMundo!**
Aqui você encontra toda a documentação e informações referentes ao sistema desenvolvido durante o 3º ano do Ensino Médio Integrado ao Técnico em Informática para Internet, realizado na ETEC de Campo Limpo Paulista.

Grupo do trabalho: 
Rayssa Aparecida da Silva Sousa Branco(Back-end, Front-end, Artigo)
Beatriz Souza Dias de Oliveira (Artigo, Front-end)
Maria Clara Cardoso Cagniato (Front-end)
Hana Nayuki de Assis Pereira (Front-end)

Orientadoras: Thaynara Cristina Maia dos Santos e Barbara Kathellen Andrade Porfirio.

**☕ Como começamos e qual é o nosso objetivo**

Nas Escolas Técnicas Estaduais de São Paulo, sempre somos incentivados a desenvolver projetos que unam aprendizado, criatividade, tecnologia e trabalho em equipe. Esses projetos têm como objetivo ampliar nossas habilidades, promover novas experiências e preparar o estudante para os desafios acadêmicos e profissionais. Durante o 3º ano do curso de Informática para Internet, vivenciamos diversas atividades práticas que unem teoria e desenvolvimento real, e foi nesse contexto que nasceu o projeto CiaoMundo.

O interesse crescente dos jovens brasileiros por oportunidades acadêmicas internacionais sempre esteve presente nas conversas entre os alunos. Muitos demonstravam o desejo de estudar fora do país, conhecer novas culturas e ampliar suas possibilidades profissionais. No entanto, também era muito comum ouvirmos relatos sobre as dificuldades enfrentadas durante esse processo: informações espalhadas em diferentes sites, falta de orientação, prazos confusos e ausência de ferramentas que ajudassem no planejamento financeiro e na busca de bolsas adequadas ao perfil do estudante.

Com a aplicação de entrevistas e questionários, percebemos que a maioria dos jovens tinha dúvidas sobre onde encontrar informações confiáveis, como iniciar o processo de candidatura e quais eram os documentos necessários. Além disso, muitos estudantes relatavam que não sabiam filtrar bolsas por país ou área de estudo, não entendiam os requisitos e não tinham acesso a um ambiente que reunisse tudo de forma clara. Essa falta de centralização de dados dificultava a jornada dos interessados e, muitas vezes, acabava desmotivando aqueles que tinham potencial e desejo de estudar no exterior.

Diante desse cenário, identificou-se a necessidade de criar uma plataforma digital completa, capaz de reunir informações sobre bolsas internacionais de graduação, oferecer ferramentas de organização, disponibilizar filtros personalizados e aproximar os estudantes de oportunidades reais. Assim nasceu o CiaoMundo, um sistema construído para facilitar a busca por bolsas de estudo, auxiliar no planejamento financeiro, exibir documentos essenciais, enviar notificações sobre prazos importantes e até sugerir oportunidades com base no perfil do usuário.

O objetivo principal do projeto é democratizar o acesso à informação e apoiar jovens brasileiros em sua jornada rumo ao ensino superior internacional. A plataforma foi pensada para ser intuitiva, acessível e eficiente, permitindo que estudantes encontrem, em um único lugar, tudo aquilo que precisam para iniciar sua trajetória acadêmica fora do Brasil.

**🎞️ Algumas imagens para demonstrar o sistema**

<p align="center">
  <img src="https://github.com/user-attachments/assets/f51b0186-d760-40b0-b318-7e619ca7faaa" height="300">
</p>

<img width="1286" height="599" alt="image" src="https://github.com/user-attachments/assets/3266495b-e3f5-4586-bfb4-43fe2cee0855" />

<img width="1282" height="606" alt="image" src="https://github.com/user-attachments/assets/e2437704-d9ec-44ed-847d-b89b0fc21071" />

<img width="1277" height="597" alt="image" src="https://github.com/user-attachments/assets/97802b22-9227-4cfc-85c6-20b51fcfe79b" />




**🖥️ Linguagens e Tecnologias Utilizadas no Website**

-Frontend

-HTML5

-CSS3

-JavaScript (ECMAScript 2023)

-Backend

-Node.js – Versão 20

-NPM – Versão 10

-MySQL – Banco de dados relacional

Dependências utilizadas

-Express

-MySQL2

-Nodemon

-Bcrypt

-CORS

-Body-parser

**✔️ Etapas de Desenvolvimento do Projeto**

-Definição do tema e defesa.

-Pesquisa bibliográfica, entrevistas e questionários.

-Levantamento de requisitos.

-Criação de protótipos no Figma.

-Desenvolvimento do frontend.

-Desenvolvimento do backend e banco de dados.

-Integração das funcionalidades.

-Testes gerais da plataforma.

-Documentação do sistema.


**🧰 Como Executar o Projeto Localmente**

1️⃣ Instalação das ferramentas necessárias

-Instale o Node.js

-https://nodejs.org/

-Instale o Visual Studio Code

-https://code.visualstudio.com/

-Instale o MySQL ou XAMPP

-https://www.apachefriends.org/pt_br/download.html

2️⃣ Configurar o Banco de Dados

-Abra o MySQL/phpMyAdmin.

-Crie o banco com o nome:

-sistema_bolsas

-Importe o arquivo SQL enviado com o projeto.

-Verifique se todas as tabelas foram criadas corretamente (usuarios, bolsas, recomendacao, simulador etc.).

-Copie o código de permissão ao administrado presente na pasta anotações do arquivo e execute. 

3️⃣ Configurar o Backend

-Abra a pasta do projeto no VS Code.

-No terminal, instale as dependências:

-npm install


Configure o arquivo de conexão:

/src/config/db.js


Com base no padrão usado no TCC:

user: 'rayssa',
password: '123',
database: 'sistema_bolsas'


Inicie o servidor:

npm start

4️⃣ Executando o Frontend

-Coloque os arquivos da pasta /public dentro da pasta correspondente.

-Inicie o servidor e abra o navegador no endereço:

https: localhost:3000/home.html

**🧩 Principais Funcionalidades**
Para o Candidato:

-Visualizar bolsas

-Filtrar por país, curso ou universidade

-Comentar em bolsas

-Avaliar bolsas com estrelas

-Salvar favoritas

-Simular custos

-Acessar guia de documentos

-Realizar teste de personalidade

-Receber notificações

-Para o Administrador

-Gerenciar usuários

-Gerenciar bolsas

-Cadastrar, editar e excluir oportunidades


**🧠 Modelagem do Sistema**


-Diagrama de Caso de Uso

-DER

-Protótipos de média fidelidade


**Resultados**

Com base nas análises dos questionários mais de 97% dos alunos desejam estudar fora, os maiores medos são custo de vida, moradia e idioma, 89% querem uma calculadora de custos.

A maioria nunca usou plataformas de bolsas, redes sociais são o meio mais usado para informações, mas confusas. O sistema CiaoMundo resolve esses problemas ao oferecer centralização, filtros eficientes, notificações e recursos interativos úteis.

**Considerações Finais**

O CiaoMundo demonstra como soluções tecnológicas podem democratizar o acesso ao ensino superior internacional. A plataforma organiza e simplifica informações importantes, reduz barreiras e incentiva jovens brasileiros a buscarem oportunidades no exterior.

**🖇️ Como citar este trabalho**

BRANCO, Rayssa Aparecida da Silva Sousa et al. Desenvolvimento de uma plataforma para auxiliar jovens brasileiros na busca por bolsas de estudos internacionais de graduação. Orientadores: Thaynara Cristina Maia dos Santos, Barbara Kathellen Andrade Porfirio. 2025. Artigo Científico, apresentado como Trabalho de Conclusão de Curso (Ensino Técnico em Informática para Internet) – ETECAMP: ETEC de Campo Limpo Paulista, Campo Limpo Paulista, 2025.

[Trabalho na íntegra](https://ric.cps.sp.gov.br/handle/123456789/41936)

