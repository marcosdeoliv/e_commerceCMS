    // Adiciona funcionalidade para trocar a imagem principal ao clicar na miniatura
       // Função para atualizar a imagem principal ao clicar em uma miniatura
       document.addEventListener('DOMContentLoaded', function () {
        const mainImage = document.getElementById('main-image'); // Seleciona a imagem principal
        const thumbnails = document.querySelectorAll('.thumbnail'); // Seleciona todas as miniaturas

        // Adiciona um evento de clique para cada miniatura
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function () {
                // Atualiza o src da imagem principal com o src da miniatura clicada
                mainImage.src = this.src;
            });
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        // Filtrar produtos por categoria
        const filterButtons = document.querySelectorAll('.filter-btn');
        const products = document.querySelectorAll('.product');
    
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const category = button.getAttribute('data-category');
    
                // Atualizar botões ativos
                filterButtons.forEach(btn => btn.classList.remove('btn-primary'));
                button.classList.add('btn-primary');
    
                // Mostrar/ocultar produtos
                products.forEach(product => {
                    const productCategory = product.getAttribute('data-category');
                    if (category === 'all' || category === productCategory) {
                        product.style.display = 'block';
                    } else {
                        product.style.display = 'none';
                    }
                });
            });
        });
    
        // Avaliação por estrelas
        const ratings = document.querySelectorAll('.rating');
    
        ratings.forEach(rating => {
            rating.addEventListener('click', e => {
                if (e.target.classList.contains('star')) {
                    const stars = Array.from(rating.children);
                    const value = e.target.getAttribute('data-value');
    
                    // Remover estado ativo anterior
                    stars.forEach(star => star.classList.remove('active'));
    
                    // Adicionar estado ativo às estrelas selecionadas
                    stars.forEach(star => {
                        if (star.getAttribute('data-value') <= value) {
                            star.classList.add('active');
                        }
                    });
    
                    alert(`Você avaliou com ${value} estrela(s)!`);
                }
            });
        });
    });
    