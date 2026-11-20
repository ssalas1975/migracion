setTimeout(function(){
    const inputs = document.querySelectorAll('input[type="checkbox"]');
    inputs.forEach( (e) => {
        e.setAttribute("required", "required");
    })
    
    const selectedInput = document.querySelector('input[type="submit"]').addEventListener('click', function(e){
        
        const id1 = document.querySelector('#id-1');
        const id2 = document.querySelector('#id-2');
        const id3 = document.querySelector('#id-3');
        
        const rand5 = document.querySelector('#rand-5');
        const rand6 = document.querySelector('#rand-6');
        const rand7 = document.querySelector('#rand-7');
        const rand8 = document.querySelector('#rand-8');
        const rand9 = document.querySelector('#rand-9');
        const rand10 = document.querySelector('#rand-10');
        const rand11 = document.querySelector('#rand-11');
        const rand12 = document.querySelector('#rand-12');
    
        
        if(id1.value === null || id2.value === null || id3.value === '0'){
            return true;
        }
        if(rand5.checked === false && rand6.checked === false && rand7.checked === false && rand8.checked === false && rand9.checked === false && rand10.checked === false){
            alert('Debes indicar como has conocido el evento');
            return true;
        }
        if(rand11.checked === false || rand12.checked === false){
            alert('Debes aceptar los textos legales');
            return true
        }
        
        e.preventDefault();
        

        var newDiv = document.createElement("div");
        newDiv.className = 'secondform';
        newDiv.innerHTML = '<div class="custom-checkbox field-columns-0"><div class="options"><input type="radio" name="consentRadio" value="1" id="rand-10001" required="required"><label for="rand-5">SÍ CONSIENTO / AUTORIZO</label></div><div class="options"><input type="radio" name="consentRadio" value="0" id="rand-10002" required="required"><label for="rand-6">NO CONSIENTO / AUTORIZO</label></div><div><input type="submit" id="btn_continuar" value="CONTINUAR"><input type="submit" id="btn_cancelar" value="CANCELAR"></div></div>'; 
        newDiv.style.cssText = 'padding: 30px;width: 96%;height: 95%;border: 1px solid black;top: 2%;float: right;position: absolute;left: 2%;text-align: left;z-index: 10000;background-color: rgba(0, 0, 0, 0.6);';
        const contenedor = document.querySelector('.sh-column-47c09eb8afa8a8a6dfe3e3cd4b09c735');
        contenedor.appendChild(newDiv);
        
        
        document.querySelector('#btn_continuar').addEventListener('click', function(e){
            const rand10001 = document.querySelector('#rand-10001').checked;
            const rand10002 = document.querySelector('#rand-10002').checked;
            if(rand10001 === true){
                const i = document.createElement("input");
                i.setAttribute("type", "text");
                i.setAttribute("value", "SI");
                i.setAttribute('name','10003');
                i.style.cssText = 'display:none;';
                document.querySelector('.fw_form_fw_form').appendChild(i);
            }
            if(rand10002 === true){
                const i = document.createElement("input");
                i.setAttribute("type", "text");
                i.setAttribute("value", "NO");
                i.setAttribute('name','10003');
                i.style.cssText = 'display:none;';
                document.querySelector('.fw_form_fw_form').appendChild(i);
            }
            document.querySelector('.secondform').remove();
            document.querySelector('.fw_form_fw_form').submit();
        });
        
        document.querySelector('#btn_cancelar').addEventListener('click', function(e){
            document.querySelector('.secondform').remove(); 
        });
        

    })

}, 3000);