$(document).ready(function() {

    loadPaginacao();

    // salvar registro com ajax
    $('#form_salvar').submit(function(e){
        e.preventDefault();
        var btnSalvar = $('input[type=submit]');
        $(btnSalvar).attr("disabled","true");
        $(btnSalvar).attr("value","Aguarde...");
        let form = $(this)[0];
        let formData = new FormData(form);
        console.log("chamou esse form_salvar submit!");
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    console.log("evt.lengthComputable: "+evt.lengthComputable);
                    console.log("evt.total: "+evt.total);
                  if (evt.lengthComputable) {              
                    var percentComplete = evt.loaded / evt.total;
                    percentComplete = parseInt(percentComplete * 100);
                    $('.progress').show();
                    $('.progress-bar').css('--width', percentComplete);
                    $('#porcentagem').text(percentComplete + "%");
                    $('.spinner-border').hide();
                  }
                }, false);
                return xhr;
              },
            type: 'POST',
            url: 'save_update.php',
            processData: false,
            contentType: false,
            data: formData,
            success: function(data){
               // console.log(data);
                let dados = JSON.parse(data);     
                if(dados.msg_cadastro != undefined &&  dados.msg_cadastro.length > 0){
                   // $('#msg-cadastro').html(dados.msg_cadastro);
                    $('.alert-cadastrado').fadeTo(2000,500).slideUp(500,function(){
                        $('.alert-cadastrado').slideUp(1000);
                    }); 
                   loadPaginacao();
                   $(btnSalvar).removeAttr("disabled");
                   $(btnSalvar).attr("value","Salvar");
                    $('#form_salvar')[0].reset(); 
                    var segundos = 3;
                    var txtPorcentagem = $('span#porcentagem').text();
                    var interval = setInterval(()=>{
                        segundos--;
                        $('span#porcentagem').text(txtPorcentagem+" aguarde uns segundos..."+segundos);
                       if(segundos == 0){
                           clearInterval(interval);   
                           $('.progress').hide();                         
                       }
                    },1000);
                }
                if(dados.erro_img != undefined && dados.erro_img.length > 0){
                    $('#msg-erro-img').html(dados.msg_erro_img);
                }
            }
        });
    });

    //update no registro com ajax no modal
    $(document).on('click','.btn-update',function(){
        var btnUpdate = $('.btn-update');
        $(btnUpdate).attr("disabled","true");
        $(btnUpdate).html('Aguarde...');
        let dados = $('#form-update').serialize();
        let form = $('#form-update')[0];
        let formData = new FormData(form);
        if(dados.length > 0){
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                      if (evt.lengthComputable) {              
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        $('.spinner-border').hide();
                        $('.progress').show();
                        $('.progress-bar').css('--width', percentComplete);
                        $('#porcentagem').text(percentComplete + "%");
                      }
                    }, false);
                    return xhr;
                  },
                type: 'POST',
                url: 'save_update.php',
                processData: false,
                contentType: false,
                data: formData,
                success: function(data){
                    let dados = JSON.parse(data);
                    if(dados.msg_atualizacao != undefined &&  dados.msg_atualizacao.length > 0){
                        $(btnUpdate).removeAttr("disabled");
                        $(btnUpdate).html('Atualizar');
                        $('#modal-content').html("");
                        //$('#msg-atualizacao').html(dados.msg_atualizacao);
                        $('.alert-atualizado').fadeTo(2000,500).slideUp(500,function(){
                            $('.alert-atualizado').slideUp(1000);
                        });
                        
                        $('.modal').modal('hide');
                        loadPaginacao();
                        var segundos = 3;
                        var txtPorcentagem = $('span#porcentagem').text();
                        var interval = setInterval(()=>{
                            segundos--;
                            $('span#porcentagem').text(txtPorcentagem+" aguarde uns segundos..."+segundos);
                            if(segundos == 0){
                                clearInterval(interval);   
                                $('.progress').hide();                         
                            }
                        },1000);
                    }
                    if(dados.erro_img != undefined && dados.erro_img.length > 0){
                        $('#msg-erro-img').html(dados.msg_erro_img);
                    }
                }
            });
        }
    
    });

    $(document).on('click','.show-loading', function(){
        $('.progress').toggle('display');
    });

    //preencher dados no modal
    $(document).on('click','.btnEdit', function(){
        let id = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: 'edit.php',
            data: {
                id: id
            },
            success: function(data){
                $('.modal').modal('show');
                $('#modal-content').html(data);
            }
        });
    });
     //deletar objeto
     $(document).on('click','.btnDelet', function(){
        if(confirm('Deseja apagar?')){
            let id = $(this).attr('data-id');    
            $.ajax({
                type: "GET",
                url: "delete.php",
                data:{
                    id: id
                },
                success: function(response){
                   // $('#msg-delete').html(response);
                   $('.alert-deletado').fadeTo(2000,500).slideUp(500,function(){
                        $('.alert-deletado').slideUp(1000);
                    });
                
                    loadPaginacao();
                } 
            });
        }  
    });
    
    $(document).on('click','#list_controle',function(){
        $('.btnDelet').toggle('display');
        $('.checkBoxDel').toggle('display');
        $('#apagar_todos').toggle('display');
        $('#selecionar_todos').toggle('display');
    });

    $(document).on('click','#selecionar_todos',function(){
        let list_checkbox =  $('.checkBoxDel');
        list_checkbox.prop("checked", !list_checkbox.prop("checked"));
    });

    $(document).on('mouseenter', 'img#list_controle', function(event) {
        $(this).css("cursor", "pointer");
    });
    $(document).on('mouseenter', '.btnDelet', function(event) {
        $(this).css("cursor", "pointer");
    });
    

    $(document).on('click','#apagar_todos',function(){
        let list_check = $("[type=checkbox]:checked");
        let list_remove = [];
        
        $(list_check).each(function(element){
            let valor = $(this).val();
            list_remove.push(valor);
        });

        if(list_remove.length >= 1){
            $.ajax({
                type: 'GET',
                url: 'delete_todos.php',
                data:{
                    list_remove: list_remove
                },
                success:function(data){
                    loadPaginacao();
                    //$('#remover_todos').show();
                    $('#remover_todos').fadeTo(2000,500).slideUp(500,function(){
                        $('#remover_todos').slideUp(1000);
                    });
                    
                }
            });
        }else{
            $('.selecione-registro').attr("aria-hidden",false);
            $('.selecione-registro').fadeTo(2000,500).slideUp(500,function(){
                $('.selecione-registro').slideUp(1000);
            });
        }
       
    });

    $(document).on('click','#btn-selecione-registro', function(){
        $('.selecione-registro').hide();
    });
    $(document).on('click','#btn-alert-cadastrado', function(){
        $('.alert-cadastrado').hide();
    });
    $(document).on('click','#btn-alert-atualizado', function(){
        $('.alert-atualizado').hide();
    });
    $(document).on('click','#btn-alert-deletado', function(){
        $('.alert-deletado').hide();
    });
    //load table com paginação ativa
    function loadTable(){
        var botaoAtivo = $('a.active');
        var dataPag = $(botaoAtivo).attr('data');
        var page = dataPag.split("&")[0];
        var limit = dataPag.split("&")[1];
        console.log("page: "+page+" limit: "+limit);
        
        $.ajax({
            type: "GET",
            url: "pessoas_json.php",
            data: {
                page: page,
                limit: limit
            }
        }).done(function(dados) {
            let row = '';
            vetDados = eval(dados);
            row = preencherTabela(vetDados);
            $('table tbody#corpo').html(row);

            registros = "<p id='total_registro' class='offset-md-10 mb-3' >"
                            "Total de registros:"+vetDados.length+" </p>";
            $('#total_registro').replaceWith(registros);
        });

    }
        function loadPaginacao(){

            $.ajax({
                type: "GET",
                url: "pessoas_copia.php",
                data: {
                    select_pag: $('#select_pag').val(),
                    pagina: 0
                }
            }).done(function(msg) {
                $('div.div_pag').replaceWith(msg);
                preencherIconesPaginacao();
                //define o primeiro botão como ativo css
                var arrayBtnPaginacao = $('a.btn-ajax');
                arrayBtnPaginacao[0].classList.add('active');
            });
        }


    $(document).ajaxStart(function() {
        $('.spinner-border').show();
    }).ajaxStop(function() {
        setTimeout(function() {
            $('.spinner-border').hide();
        }, 500);
    });
    $(document).on('mouseenter', '.btnEdit', function(event) {
        $('.btnEdit').css("cursor", "pointer");
    });
    // cursor pointer mouse hover sort
    $(document).on('mouseenter', 'img.foto_sort', function(event) {
        $('img.foto_sort').css("cursor", "pointer");
    });

    //como eu conseguir pensar nisso!?! hueahuaehueahueahuea
    // click para ordenacao na img do header tabela
    $(document).delegate('img.foto_sort', 'click', function() {

        //resetar botoes checkbox
        resetarBotoesCheck();
        var tipo = $(this).attr("data");
        var sort = '';
        var botaoAtivo = $('a.active');
        var dataPag = $(botaoAtivo).attr('data');
        var page = dataPag.split("&")[0];
        var limit = dataPag.split("&")[1];


        if ($(this).attr("src") == './icons/d-sort.png') {
            // trocando a img
            $(this).attr("src", "./icons/u-sort.png");
            getOrdenacao(tipo, "ASC", page, limit);
        } else if ($(this).attr("src") == './icons/u-sort.png') {
            // trocando a img
            $(this).attr("src", "./icons/d-sort.png");
            getOrdenacao(tipo, "DESC", page, limit);
        }

        function getOrdenacao(param_tipo, param_sort, page, limit) {

            $.ajax({
                type: "GET",
                url: "sort.php",
                data: {
                    tipo: param_tipo,
                    sort: param_sort,
                    page: page,
                    limit: limit
                }
            }).done(function(dados) {
                $('table tbody#corpo').replaceWith(dados);
            });
        }
    });

    function resetarBotoesCheck(){
        $('#apagar_todos').hide();
        $('#selecionar_todos').hide();
    }

    //pesquisa com keyup
    $('#pesquisa').keyup(function(event) {

        //resetar botões de checkbox 
        resetarBotoesCheck();

        var pesquisa = $(this).val();
        var botaoAtivo = $('a.active');
        var dataPag = $(botaoAtivo).attr('data');
        var page = dataPag.split("&")[0];
        var limit = dataPag.split("&")[1];

        $.ajax({
            type: "GET",
            url: "pesquisa.php",
            data: {
                pesquisa: pesquisa,
                page: page,
                limit: limit
            }
        }).done(function(data) {
            $('table tbody#corpo').hide();
            $('table tbody#corpo').replaceWith(data);
        });
    });

    //ajax para paginação do php na index
    $(document).delegate('a.btn-ajax', 'click', function(e) {
        e.preventDefault();
        var valor = $(this).attr('data');
        var partes = valor.split("&")
        //alert('Pagina: '+partes[0]+  ' Paginação: '+ partes[1]);
        var textLink = $(this).text();
        $.ajax({
            type: "GET",
            url: "pessoas_copia.php",
            data: {
                pagina: partes[0],
                select_pag: partes[1]
            }
        }).done(function(data) {
            $('div.div_pag').replaceWith(data);
            $('a.btn-ajax').each(function(index, element) {
                if ($(this).attr('data') == valor && $(this).text().trim() == textLink.trim()) {      
                    var li = $(this).parent()[0];
                    $(this).addClass('active');
                    li.classList.add('active');
                }
            });
            preencherIconesPaginacao();
        });
    });

    function preencherIconesPaginacao() {
        $('a.btn-ajax').each(function(i, element) {
            if ($(element).text().trim() == 'Primeiro') {
                let img = "<img src='./icons/ultimo.png' width='16' height='16' alt='' />";
                $(this).prepend(img);
            }
            if ($(element).text().trim() == 'Último') {
                let img = '<img src="./icons/primeiro.png" width="16" height="16" alt="" />';
                $(this).append(img);
            }
        });
    }
    //ajax para fazer paginação com select
    $('#select_pag').change(function() {
        $.ajax({
            type: "GET",
            url: "pessoas_copia.php",
            data: {
                select_pag: $('#select_pag').val(),
                pagina: 0
            }
        }).done(function(msg) {
            $('div.div_pag').replaceWith(msg);
            preencherIconesPaginacao();
            //define o primeiro botão como ativo css
            var arrayBtnPaginacao = $('a.btn-ajax');
            arrayBtnPaginacao[0].classList.add('active');
        });


    });

});