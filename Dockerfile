# Используем официальный образ Elasticsearch
FROM docker.elastic.co/elasticsearch/elasticsearch:8.13.4

# Копируем измененный файл конфигурации в контейнер
COPY elasticsearch.yml /usr/share/elasticsearch/config/elasticsearch.yml

# Устанавливаем владельца и права доступа к файлу конфигурации
USER root
RUN chown elasticsearch:elasticsearch /usr/share/elasticsearch/config/elasticsearch.yml
USER elasticsearch

