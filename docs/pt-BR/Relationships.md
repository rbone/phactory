# Relacionamentos

Os objetos não vivem no vácuo, inevitavelmente um objeto dependerá da existência
de outro, e é aqui que entram os relacionamentos. Implementar um relacionamento
é fácil:

```php
<?php

class LivroPhactory
{
    public function blueprint()
    {
        return array(
            'title' => 'Um Estudo em Vermelho',
            'author' => Phactory::hasOne('autor'),
        );
    }
}

class AutorPhactory
{
    public function blueprint()
    {
        return array(
            'name' => 'Sir Arthur Conan Doyle',
        );
    }
}

```

Como você pôde perceber, implementar um relacionamento é tão fácil quanto
escrever `Phactory::hasOne('type')`. Todas as funcionalidades normais da Phactory
também estão disponíveis para os relacionamentos, variações e substituições podem
ser usados passando um segundo ou terceiro parâmetro.
