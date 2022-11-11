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
            {if $sort == 'date' && $direction == 'asc'}
                {assign var="nextDirDate" value="desc"}
                {assign var="dateArrow" value="▲"}
            {else}
                {assign var="nextDirDate" value="asc"}
                {assign var="dateArrow" value=$sort == 'date' ? '▼' : ''}
            {/if}
            {if $sort == 'views' && $direction == 'asc'}
                {assign var="nextDirViews" value="desc"}
                {assign var="viewsArrow" value="▲"}
            {else}
                {assign var="nextDirViews" value="asc"}
                {assign var="viewsArrow" value=$sort == 'views' ? '▼' : ''}
            {/if}
            <a href="index.php?page=category&id={$category.id}&sort=views&dir={$nextDirViews}&p=1" class="{if $sort == 'views'}active{/if}">
                Views{if $viewsArrow} {$viewsArrow}{/if}
            </a>
            <a href="index.php?page=category&id={$category.id}&sort=date&dir={$nextDirDate}&p=1" class="{if $sort == 'date'}active{/if}">
                Date{if $dateArrow} {$dateArrow}{/if}
            </a>
        </div>
    </div>

    {if $articles|@count == 0}
        <p class="empty-state">No articles in this category.</p>
    {else}
        <ul class="article-cards">
            {foreach $articles as $art}
                <li>
                    <a href="index.php?page=article&id={$art.id}" class="article-card">
                        <div class="article-card-image-wrap">
                            {if $art.image}
                                <img src="{$art.image}" alt="" class="article-card-image" loading="lazy">
                            {/if}
                        </div>
                        <div class="article-card-body">
                            <h3 class="article-card-title">{$art.title}</h3>
                            <span class="article-card-meta">{$art.views} views · {$art.published_at|date_format:"%B %d, %Y"}</span>
                            {if $art.description}
                                <p class="article-card-desc">{$art.description}</p>
                            {/if}
                            <span class="article-card-link">Continue Reading</span>
                        </div>
                    </a>
                </li>
            {/foreach}
        </ul>

        {if $totalPages > 1}
            <nav class="pagination" aria-label="Pagination">
                {if $currentPage > 1}
                    <a href="index.php?page=category&id={$category.id}&sort={$sort}&dir={$direction}&p={$currentPage - 1}" class="pagination-prev">Previous</a>
                {/if}
                <span class="pagination-info">Page {$currentPage} of {$totalPages}</span>
                {if $currentPage < $totalPages}
                    <a href="index.php?page=category&id={$category.id}&sort={$sort}&dir={$direction}&p={$currentPage + 1}" class="pagination-next">Next</a>
                {/if}
            </nav>
        {/if}
    {/if}
{/block}
