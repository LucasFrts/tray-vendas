<x-mail::message>
# Relatório de Vendas do Dia

Olá {{ $seller->name }},

Aqui está um resumo das suas vendas realizadas hoje ({{ now()->format('d/m/Y') }}):

- **Quantidade de vendas:** {{ $count }}
- **Valor total das vendas:** R$ {{ number_format($total, 2, ',', '.') }}
- **Valor total de comissões:** R$ {{ number_format($commission, 2, ',', '.') }}

<x-mail::button :url="url('/dashboard')">
Acessar Dashboard
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
