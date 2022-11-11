{* Smarty *}
{extends file="layouts/base.tpl"}
{block name="content"}
    <h1 class="page-title">{$category.title}</h1>
    {if $category.description}
        <p class="lead">{$category.description}</p>
    {/if}

    <div class="toolbar">
        <span class="total">{$total} article(s)</span>
        <div class="sort-links">
            Sort by:
            <a href="index.php?page=category&id={$category.id}&sort=date&p=1" class="{if $sort == 'date'}active{/if}">Date</a>
            <a href="index.php?page=category&id={$category.id}&sort=views&p=1" class="{if $sort == 'views'}active{/if}">Views</a>
        </div>
    </div>

    {if $articles|@count == 0}
        <p class="empty-state">No articles in this category.</p>
    {else}
        <ul class="article-list">
            {foreach $articles as $art}
                <li class="article-item">
                    <a href="index.php?page=article&id={$art.id}" class="article-link">
                        {if $art.image}
                            <img src="{$art.image}" alt="" class="article-thumb" loading="lazy">
                        {/if}
                        <div class="article-info">
                            <h3 class="article-title">{$art.title}</h3>
                            {if $art.description}
                                <p class="article-desc">{$art.description}</p>
                            {/if}
                            <span class="article-meta">{$art.published_at|date_format:"%b %d, %Y"} · {$art.views} views</span>
                        </div>
                    </a>
                </li>
            {/foreach}
        </ul>

        {if $totalPages > 1}
            <nav class="pagination" aria-label="Pagination">
                {if $currentPage > 1}
                    <a href="index.php?page=category&id={$category.id}&sort={$sort}&p={$currentPage - 1}" class="pagination-prev">Previous</a>
                {/if}
                <span class="pagination-info">Page {$currentPage} of {$totalPages}</span>
                {if $currentPage < $totalPages}
                    <a href="index.php?page=category&id={$category.id}&sort={$sort}&p={$currentPage + 1}" class="pagination-next">Next</a>
                {/if}
            </nav>
        {/if}
    {/if}
{/block}
