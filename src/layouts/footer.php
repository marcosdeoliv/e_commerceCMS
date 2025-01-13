<footer class="bg-dark text-light py-4">
  <div class="container">
    <div class="row">
      <!-- Coluna 1 -->
      <div class="col-md-3">
        <a class="navbar-brand" href="#">
          <img src="assets/imgs/logo.png" width="40px" />
        </a>
        <h5>PruthCherry</h5>
        <p>Pruth Cherry is dedicated with intensity and purpose to strengthening Education.</p>
<?php if (isset($_SESSION['user_id'])): ?>
                  <a class="nav-link" href="./auth/account_details.php" title="Perfil">
                   <p>Minha conta</p>
                    </a>
                    <!-- Logout -->
                    <a class="nav-link text-danger" href="./auth/logout.php" title="Sair">
                            <p class="fas fa-sign-out-alt">Sair</p>
                        </a>
                    </li>
    <?php else: ?>
     <!-- Login -->
     <a class="nav-link" href="./auth/login.php" title="Entrar">
        <p class="fas fa-sign-in-alt">Entrar</p>
        </a>
        <!-- Cadastro -->
           <a class="nav-link" href="./auth/register.php" title="Cadastrar">
              <p class="fas fa-user-plus">Registre-se</p>
                        </a>
                    </li>
      <?php endif; ?> 
      </div>
      <!-- Coluna 2 -->
      <div class="col-md-3">
        <h5>Links Úteis</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="text-light text-decoration-none">Home</a></li>
          <li><a href="#" class="text-light text-decoration-none">Produtos</a></li>
          <li><a href="#" class="text-light text-decoration-none">Blog</a></li>
          <li><a href="#" class="text-light text-decoration-none">Contato</a></li>
        </ul>
      </div>
      <!-- Coluna 3 -->
      <div class="col-md-3">
        <h5>Contato</h5>
        <ul class="list-unstyled">
          <li><i class="fa fa-map-marker-alt me-2"></i>Rua Kdê menina?, 123</li>
          <li><i class="fa fa-phone me-2"></i>(65) 1234-5678</li>
          <li><i class="fa fa-envelope me-2"></i>artefatus@prothcrerry.com</li>
        </ul>
      </div>
      <!-- Coluna 4 -->
      <div class="col-md-3">
        <h5>Instagram</h5>
        <div class="d-flex flex-wrap gap-3">
          <img src="assets/imgs/cake.png" alt="Bolo" class="img-fluid" style="width: 50px; height: auto;">
          <img src="assets/imgs/books.png" alt="Livros" class="img-fluid" style="width: 50px; height: auto;">
          <img src="assets/imgs/coffee.png" alt="Café" class="img-fluid" style="width: 50px; height: auto;">
          <img src="assets/imgs/storytelling.png" alt="Histórias" class="img-fluid" style="width: 50px; height: auto;">
        </div>
      </div>
    </div>
    <!-- Linha e Mensagem de Copyright -->
    <hr class="bg-light my-5">
    <div class="text-center">
      <p class="mb-0">&copy; 2025 PruthCherry. Todos os direitos reservados.</p>
      <h5>Redes Sociais</h5>
      <a href="#" class="text-light me-3"><i class="fab fa-facebook"></i></a>
      <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
      <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
      <a href="#" class="text-light"><i class="fab fa-linkedin"></i></a>
    </div>
  </div>
</footer>