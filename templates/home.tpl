{* Smarty *}
{extends file="layouts/base.tpl"}
{block name="content"}
    {if $categoriesWithRecent|@count == 0}
        <p class="empty-state">No categories with articles yet. <a href="index.php?action=seed">Run the seeder</a> to add sample data.</p>
    {else}
        {foreach $categoriesWithRecent as $item}
            <section class="category-section">
                <div class="category-section-header">
                    <h2 class="category-section-title">{$item.category.title}</h2>
                    <a href="index.php?page=category&id={$item.category.id}" class="view-all-link">View All</a>
                </div>
                <ul class="article-cards">
                    {foreach $item.recent_posts as $post}
                        <li>
                            <a href="index.php?page=article&id={$post.id}" class="article-card">
                                <div class="article-card-image-wrap">
                                    {if $post.image}
                                        <img src="{$post.image}" alt="" class="article-card-image" loading="lazy">
                                    {/if}
                                </div>
                                <div class="article-card-body">
                                    <h3 class="article-card-title">{$post.title}</h3>
                                    <span class="article-card-meta">{$post.published_at|date_format:"%b %e, %Y"}</span>
                                    {if $post.description}
                                        <p class="article-card-desc">{$post.description}</p>
                                    {/if}
                                    <span class="article-card-link">Continue Reading</span>
                                </div>
                            </a>
                        </li>
                    {/foreach}
                </ul>
            </section>
        {/foreach}
    {/if}
{/block}
