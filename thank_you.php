<?php
session_start();

// Incluir arquivo de configuração do banco de dados
include 'includes/db.php';

// Verificar se a sessão do pedido existe
if (!isset($_SESSION['order'])) {
    header("Location: index.php");
    exit();
}

// Obter os detalhes do pedido
$order = $_SESSION['order'];
unset($_SESSION['order']); // Limpar os detalhes do pedido após a exibição
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obrigado pela Compra</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
    <div class="container">
        <h1>4PETS</h1>
        <nav class="navbar">

        
            <div class="nav-left">
                <a href="index.php">
                <i class="fas fa-home"></i> 
                </a>
                <a href="cart.php">
                <i class="fas fa-shopping-cart"></i> 
                </a>
            </div>
            <div class="nav-right">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="user-info">
                    <a href="profile.php">
                        <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Foto de Perfil" class="profile-picture">
                </a>
                <a href="profile.php">

                        <span href="profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
                        <a href="logout.php" class="btn">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="login.php">
                    <i class="fas fa-user"></i> Login
                    </a>
                    <a href="register.php">Criar Conta</a>
                <?php endif; ?>
            </div>
            
        </nav>
    </div>
    <Center>
    <form action="search.php" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Pesquisar produtos..." required>
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
                </Center>
    </header>

    <main>
        <div class="container thank-you">
            <h2>Obrigado pela sua compra!</h2>
            <p>Seu pedido foi realizado com sucesso. Aqui estão os detalhes da sua compra:</p>
            
            <fieldset>
                <legend>Informações do Cliente</legend>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>CPF:</strong> <?php echo htmlspecialchars($order['cpf']); ?></p>
            </fieldset>

            <fieldset>
                <legend>Endereço de Entrega</legend>
                <p><strong>Endereço:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                <p><strong>Cidade:</strong> <?php echo htmlspecialchars($order['city']); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($order['state']); ?></p>
                <p><strong>CEP:</strong> <?php echo htmlspecialchars($order['zip']); ?></p>
            </fieldset>

            <fieldset>
                <legend>Forma de Pagamento</legend>
                <p><strong>Forma de Pagamento:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
            </fieldset>

            <fieldset>
                <legend>Resumo do Pedido</legend>
                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($order['cart'] as $product_id => $item) {
                            $sql = "SELECT * FROM products WHERE id = " . intval($product_id);
                            $result = $conn->query($sql);
                            $product = $result->fetch_assoc();
                            if ($product) {
                                $item_total = $item['quantity'] * $product['price'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($item_total, 2, ',', '.'); ?></td>
                        </tr>
                        <?php
                            }
                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Subtotal:</strong></td>
                            <td>R$ <?php echo number_format($order['total'] - $order['shipping_cost'], 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>Frete:</strong></td>
                            <td>R$ <?php echo number_format($order['shipping_cost'], 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>Total:</strong></td>
                            <td>R$ <?php echo number_format($order['total'], 2, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </fieldset>
        </div>
    </main>

    <footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section about">
                <h2>SOBRE A LOJA</h2>
                <p>
                    Bem-vindo à nossa loja a 4Pets! Oferecemos uma ampla variedade de produtos com preços muito baratos, descontos e entrega rápida. Nossa missão é proporcionar a melhor experiência de compra online para nossos clientes e pets.
                </p>
                <div class="contact">
                    <i class="fas fa-phone"></i> (11) 93931-4393
                    <br>
                    <i class="fas fa-envelope"></i> THG
                </div>
                <div class="socials">
                    <a href="https://pt-br.facebook.com/s" target="_blank"><i class="fab fa-facebook" ></i></a>
                    <a href="https://www.instagram.com/_rafhaelcesar_?igsh=anRoemE0NHJ1cndq" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/5511939314393" target="_blank"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            
            <div class="footer-section links">
                <h2>LINKS </h2>
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Carrinho</a></li>
                    <li><a href="login.php"><i class="fas fa-user"></i> Login</a></li>
                    <li><a href="contact.php"><i class="fas fa-envelope"></i> Contato</a></li>
                    <li><a href="faq.php"><i class="fas fa-question-circle"></i> Sobre nós</a></li>
                </ul>
            </div>

            <div class="footer-section hours">
                <h2>HORÁRIOS DE ATENDIMENTO</h2>
                <p><i class="fas fa-clock"></i> Seg-Sex: 9h - 18h</p>
                <p><i class="fas fa-clock"></i> Sáb: 9h - 14h</p>
                <p><i class="fas fa-clock"></i> Dom: Fechado</p>
            </div>
        </div>
        <center>
        <div class="footer-bottom">
            &copy; 2024 4Pets | A melhor loja para o seu pet.
            </center>
        </div>
    </div>
           
        </div>
    </footer>



    <style>
    .product-image {
    width: 300px; /* Ajuste o tamanho base da imagem conforme necessário */
    height: 300px;
    object-fit: cover;
    transition: transform 0.3s ease; /* Adiciona uma transição suave */
}

.product-image:hover {
    transform: scale(1.2); /* Aumenta o tamanho da imagem em 20% quando o cursor passar por cima */
    cursor: zoom-in; /* Muda o cursor para o ícone de zoom */
}


.navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
}
         .nav-left, .nav-right {
        display: flex;
        align-items: center;
    }

    .nav-left a, .nav-right a {
        margin-right: 20px;
        text-decoration: none;
        font-weight: bold;
      
    }

    .nav-left a:hover, .nav-right a:hover {
        color: #0FB2BC;
    }
.bannerr {
    background-color: #ffcc00;
    color: #333;
    text-align: center;
    padding: 10px 0;
    font-size: 16px;
    position: relative;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.bannerr p {
    margin: 0;
    padding: 0;
}

.close-btn {
    font-size: 20px;
    cursor: pointer;
    margin-left: 15px;
    color: #333;
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
}

.close-btn:hover {
    color: #ff0000;
}


/* Estilos da barra de pesquisa */
.search-form {
    display: flex;
    align-items: center;
    position: relative;
    max-width: 300px;
    width: 100%;
}

.search-form input[type="text"] {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #ddd;
    border-radius: 25px;
    outline: none;
    font-size: 16px;
    transition: border-color 0.3s;
}

.search-form input[type="text"]:focus {
    border-color: #007bff;
}

.search-form button {
    position: absolute;
    right: 5px;
    background-color: transparent;
    border: none;
    cursor: pointer;
    font-size: 18px;
    color: #333;
}

.search-form button i {
    font-size: 18px;
}

.search-form button:hover {
    color: #007bff;
}



header{
    background-color: #002C5A;
    }
    footer {
    background-color: #002c5a;
    color: #fff;
    padding: 40px 0;
}

footer .container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    width: 100%;
}

.footer-section {
    flex: 1;
    margin: 10px;
}

.footer-section h2 {
    font-size: 1.5rem;
    margin-bottom: 20px;
    color: #fff;
}

.footer-section p {
    margin-bottom: 15px;
}

.footer-section .contact i,
.footer-section .hours i {
    color: #ff8c00;
    margin-right: 10px;
}

.footer-section .socials a {
    margin-right: 15px;
    color: #fff;
    font-size: 1.2rem;
}

.footer-section .socials a:hover {
    color: #ff8c00;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
}

.footer-section ul li a i {
    margin-right: 10px;
}

.footer-section ul li a:hover {
    color: #ff8c00;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    font-size: 0.9rem;
    border-top: 1px solid #444;
}
</style>
</body>
</html>