<x-mail::message>
# Relatório Geral de Vendas do Dia

Relatório consolidado das vendas de hoje ({{ now()->format('d/m/Y') }}):

- **Total de vendas realizadas:** {{ $count }}
- **Valor total vendido:** R$ {{ number_format(\$total, 2, ',', '.') }}

<x-mail::button :url="url('/admin/dashboard')">
Ver Detalhes no Painel
</x-mail::button>

Esse e-mail é gerado automaticamente pelo sistema.

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
